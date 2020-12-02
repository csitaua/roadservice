<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
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

echo menu();


$col1 = 85;
$col2 = 200;
$history_item = 3;

$sql = "SELECT * FROM service_gc WHERE id = '$id'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$st ='';
if($_SESSION['user_level'] < POWER_LEVEL){
	$st='readonly="readonly"';	
}


$sql2 = "SELECT COUNT(*) as t FROM VW_VEHICLE WHERE VehStatus='A'";
$rs2= sqlsrv_query($conn,$sql2);
$row2 = sqlsrv_fetch_array($rs2);



?>


<form name="edit_gc" enctype="multipart/form-data" action="rec_gc.php?gc=<?php echo $id;?>" method="post">
	<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center"><h8 style="color:#FFF">Service Request # <?php  echo str_pad($id,5,'0',STR_PAD_LEFT);?></h8></td>
      </tr>
        <tr><td colspan="5" align="right" style="color:#fff">Total Insured Vehicles: <?php echo $row2['t'];?></td></tr>
        <?php if($row['master_sc']!=0){
		?>
        <tr>
        	<td class="top-child" colspan="5">Service request came from#<a style="color:#DCB272" href="edit_sc.php?sc=<?php $master_sc = $row['master_sc']; echo $row['master_sc'];?>"/><?php echo str_pad($row['master_sc'],5,'0',STR_PAD_LEFT);?></td>
            
        </tr>
        <tr>
        	<td class="middle-child" colspan="5">Time Inserted: <?php echo substr($row['timestamp'],0,-3);?> &nbsp; <span style="color:blue"></span>
					<?php if($_SESSION['user_level'] >=4){?>&nbsp;<?php } ?></td>
        <?php
		} 
		else{
		?>
        <tr>
        	<td class="top-child" colspan="5">Time Inserted: <?php echo substr($row['timestamp'],0,-3);?> <span style="color:blue"></span>
					<?php if($_SESSION['user_level'] >=4){?>&nbsp;<?php } ?>
            </td>
       	<?php
		}
		?>
        
        <?php
			$sqlt="SELECT * FROM survey WHERE service_req_id=$id";
			$rst=mysql_query($sqlt);
			if($rowt=mysql_fetch_array($rst)){
				$sid=$rowt['id'];
				echo '
					 <tr>
        	<td class="middle-child" colspan="5">
			Survey: <a href="survey.php?sid='.$sid.'">'.str_pad($sid,5,'0',STR_PAD_LEFT).'</a>
			</td>
			</tr>
				';			
			}
			?>
        </tr>
        <?php
			$path='rranotification/'.$id.'/';
			if( file_exists('d:\web\roadservice\\'.$path.str_replace('/','-',$row['pol']).'_Acc_Notification.pdf')) {
			echo ' 
			<tr>
        		<td class="middle-child" colspan="5">
            	<span>
            	<div class="buttonwrapper">
					<a class="squarebutton" href="download.php?file='.$path.str_replace('/','-',$row['pol']).'_Acc_Notification.pdf'.'"><span>Download Accident Notification</span></a>
				</div>
            </span>
            	</td>  
        	</tr>';
			}
		?>
        <tr>
        	<?php
				if($_SESSION['user_level'] >= POWER_LEVEL){
			?>
            	<td class="middle-child" colspan="5">Date Of Loss: 
                <input type="text" id="opendt" name="opendt" readonly value="<?php echo $row['opendt'];?>"/>
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
        	<td class="middle-child" colspan="5">Time Requested: <?php echo $row['opendt'];?>
            <?php
				if($_SESSION['user_level'] < POWER_LEVEL){ //Input hidden
			?>
            	<input type="hidden" name="opendt" value="<?php echo $row['opendt'];?>" />
            <?php } ?>
            <?php }?>
 		</td>
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
               	colspan="5">Time Inspected: 
                <input type="text" id="arrival_time" name="arrival_time" readonly value="<?php echo $row['arrival_time'];?>"/>
  <button id="openbutton1">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#openbutton1').click(
      function(e) {
        $('#arrival_time').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
  </script> 
            <?php
			
			
				}
				else{
			?>
        	<td <?php if($row['status']!=2 && $row['status']!=3) {
						echo 'class="bottom-child"';
					}
					else{
						echo 'class="middle-child"';	
					}
				?> colspan="5">Time Arrival: <?php echo $row['arrival_time'];?>
            <?php
				if($_SESSION['user_level'] < POWER_LEVEL){ //Input hidden
			?>
            	<input type="hidden" name="arrival_time" value="<?php echo $row['arrival_time'];?>" />
            <?php } ?>
            <?php }?>
 		</td>
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
            <?php } ?>
        </tr>	
        <?php
			}
			else if($row['status']==3){ //Cancelled
		?>
          <tr>
        	<td class="bottom-child" colspan="5">Time Cancelled: <?php echo $row['closedt'];?>
        </tr>	
        <?php
			}
		?>
        <tr><td colspan="5">&nbsp;</td></tr>
        
        
       	</tr>
         <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td colspan="2" class="top-child_h" style="color:#148540" align="center">Call Information</td>
            <td colspan="2" class="top-child_h" style="color:#148540" align="center">Insurance Information</td>
            <td rowspan="25" valign="top">
            <table width="100%" cellspacing="0">
            	<tr><td class="top-child_h" colspan="2" style="color:#148540" align="center">Insurance Information</td></tr>
            	<tr><td class="middle-child" colspan="2">&nbsp;</td></tr>
                <?php	
				$lic = $row['a_number'];
				$policy = $row['pol'];
				$use_extra_empty=0;
				$use_extra = 0;
				//$sql2 = "SELECT * FROM vehicles_2 WHERE LicPlateNo = '$lic' ORDER BY STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";	
				/*$sql2 = "SELECT * FROM vehicles_2 WHERE PolicyNo LIKE '$policy' AND `LicPlateNo` = '$lic' ORDER BY Status DESC, STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";	
				$rs2 = mysql_query($sql2);*/
				$sql2 = "SELECT * FROM VW_POLICIES WHERE PolicyNo LIKE '$policy' ORDER BY 
				CASE	WHEN Status='A' THEN 1
						WHEN Status='L' THEN 2
						WHEN Status='C' THEN 3
				End
				, Date_Renewal DESC, PolicyNo DESC";
				$params = array();
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$rs2 = sqlsrv_query($conn,$sql2,$params,$options);
				$sql3 = "SELECT * FROM `non_client_extra` WHERE `id` = '$row[a_number]'";
				$rs3 = mysql_query($sql3);
				
				if(sqlsrv_num_rows($rs2)!=0 || mysql_num_rows($rs3)!=0){
					$vcol1 = 45;
					$vcol2 = 55;
					if(sqlsrv_num_rows($rs2)!=0 && trim($policy)!==''){
						$row2 = sqlsrv_fetch_array($rs2);
						$use_extra = 0;
					}
					else{
						$use_extra = 1;
						$row3 = mysql_fetch_array($rs3);
						if(mysql_num_rows($rs3)<1){
							$use_extra_empty=1;
						}
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
							}?>
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
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Risk Address:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php 
						if(!$use_extra){
							echo $row2['RiskLocation'];
							$caddress = $row2['RiskLocation'];
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
						?> ><?php echo $row2['Status'];?></td>
                    </tr>
                    <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Insured Since:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php $tdate = new datetime (substr($row2['Date_Application'],0,10));
			echo date_format($tdate,"d F, Y");?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Insured From:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php 
						if(!$use_extra){
							$fdate = new datetime(substr($row2['Date_Effective'],0,10));
							$tdate = new datetime(substr($row2['Date_Renewal'],0,10));
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
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Assignee:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php 
						if(!$use_extra){
							echo $row2['AssigneeCode'];
						}
						else{
							echo $row3['year'].' / '.$row3['color'];	
						}?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Perils Covered:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php 
						if(!$use_extra){
							echo $row2['PerilsCovered'];
						}
						else{
							echo $row3['body_type'].' / '.$row3['seats'];
						}?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Sum Insured:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php 
						if(!$use_extra){
							echo $row2['CurrCode'].' '.$row2['Sum_Insured'];
						}
						else{
							if($row3['vehicle_use']==='private'){
								$vuse='PR';	
							}
							else{
								$vuse="CM";	
							}
							echo number_format($row3['cat_value'],2).' / '.number_format(dayValue($row3['year'],$row3['cat_value'],$vuse),2);
							$vv=$row3['cat_value'];
						}
						if(trim($row['manu_date'])!=='' AND trim($row['opendt'])!==''){
						
						$t=substr($row['opendt'],6,4)."-".substr($row['opendt'],0,2)."-".substr($row['opendt'],3,2);
						
						echo ' / <a style="font-weight:bold">'.number_format(dayValueA($row['manu_date'],$t,$vv,$vuse),2).'</a>';
						}
						?></td>
                    </tr>
                     <tr>
                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Coverage:</td>
                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php 
						if(!$use_extra){
							echo $row2['Coverage'];
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
                    		<td class="middle-left-child" width="<?php echo $vcol1;?>%">Total Premium / Balance<?php if($row['job']==7 || $row['job']==8 || $row['job']==18 || $row['job']==23 || $row['job']==28 || $row['job'] == 15 ){
							echo ' / Deductible';}?>:</td>
                        	<td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php if($_SESSION['user_level'] >= ADMIN_LEVEL){echo number_format(($row2['Premium']+(float)$row2['PolicyFee']),2).' / '.number_format($row2['AmountDeb'],2);}?><?php if($row['job']==7 || $row['job']==8 || $row['job']==18 || $row['job']==23 || $row['job']==28 || $row['job'] == 15 ){
							echo ' / '.$row2['Deduct'];}?></td>
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
				else if( mysql_num_rows($rs3)==0){
					$use_extra_empty=1;
					$use_extra = 1;	
				}
				?>
                <tr><td class="top-child_h"  colspan="2" style="color:#148540" align="center">&nbsp;</td></tr>
                 <tr><td class="middle-child"  colspan="2" rowspan="3">
				 
                 <table width="100%">
                 	<tr>
                    	<td width="50%">
				 
                 		</td>
                        <td width="50%" valign="top">
                       
                            
                        </td>
                  	</tr>
             	</table>
                 </td>
                 </tr>
                 <tr><td colspan="2"></td></tr>
                <tr><td colspan="2"></td></tr>
                <tr><td class="bottom-child"  colspan="2" rowspan="3">
				 <?php
				 	$license = $row['licenseNo2'];
                 	$sql3 = "SELECT * FROM drivers_license where id = '$row[licenseNo2]'";
					$rs3 = mysql_query($sql3);
					if($row3 = mysql_fetch_array($rs3)){
				 ?>
                 <br/>
                 <br/>
                 Email: <?php  
				$sql3 = "SELECT * FROM drivers_license where id = '$row[licenseNo2]'";
				$rs3 = mysql_query($sql3);
				$row3 = mysql_fetch_array($rs3);
				echo $row3['email'];
				 ?>
                 <br/>
                 <br/>
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
												$sql9="SELECT count(sc_id) as c FROM service_gc_extra WHERE `dr_license`='$license2' AND status='Not At Fault'";
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
															$sql8="SELECT count(sc_id) as c FROM service_gc_extra WHERE `dr_license`='".$tl."' AND status='Not At Fault'";
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
												$sql9="SELECT count(sc_id) as c FROM service_gc_extra WHERE `dr_license`='$license' AND (status='Pending' OR status='')";
												$rs9=mysql_query($sql9);
												$row9=mysql_fetch_array($rs9);
												$pend =  $row9['c'];
												if(trim($pers) !== ''){
													$sql9 = "SELECT * FROM drivers_license WHERE id!='$license2' AND persoonsNo='$pers'";
													$rs9=mysql_query($sql9);
													while($row9 = mysql_fetch_array($rs9)){
														$tl = $row9['id'];
														if(trim($tl) !== ''){
															$sql8="SELECT count(sc_id) as c FROM service_gc_extra WHERE `dr_license`='".$tl."' AND (status='Pending' OR status='')";
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
												$sql9="SELECT count(sc_id) as c FROM service_gc_extra WHERE `dr_license`='$license' AND (status='At Fault' OR status='Shared Liability')";
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
															$sql8="SELECT count(sc_id) as c FROM service_gc_extra WHERE `dr_license`='".$tl."' AND (status='At Fault' OR status='Shared Liability')";
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
            </td>
        </tr>
        
   
       
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Job:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <?php if($_SESSION['user_level'] >= RR_LEVEL){
			
			?>
            <select name="job" style="background-color:#FAD090" onchange="display(this)">
                    <?php
						$jid = $row['job'];
						$sql2 = "SELECT * FROM jobs_gc order by sort_order";
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
            <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Location:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="loc" id="loc2" size="23" value="<?php echo $row['location'];?>"/></td>
            <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr>
          	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
          	<td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
            <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
         <tr>
         	<td class="middle-left-child" width="<?php echo $col1;?>">District:</td>
             <td class="middle-right-child" width="<?php echo $col2;?>"><select name="district" style="background-color:#FAD090">
                    <?php
						$sql3 = "SELECT * FROM districts order by district";
						$rs3 = mysql_query($sql3);
						if($row['district']==0){
							echo '<option selected="selected" value="0"></option>';		
						}						
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
            <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
             <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
           	
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Direction</td>
             <td class="middle-right-child" width="<?php echo $col2;?>">
             	<textarea name="direction" cols="20" rows="4" style="background-color:#FAD090;"><?php echo stripcslashes($row['direction']);?></textarea>
             </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Policy Nr.:</td>
             <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" style="background-color:#FAD090" name="pol" id="pol" size="20" value="<?php echo $row['pol'];?>"/> <?php $temp_pol=$row['pol']?></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Email</td>
             <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" style="background-color:#FAD090" name="contact_info_email" id="contact_info_email" size="20" value="<?php echo $row['contact_info_email'];?>"/> </td>
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
			?>
             </td>
        </tr>
        <tr>
       	  <td class="middle-child" colspan="2">Damages:<br/><br/>
            <table width="100%">
            <tr>
            	<td width="15%">Roof</td>
                <td width="35%"><input name="roof_dam_info" value="<?php echo $row['roof_dam_info'];?>" style="background-color:#FAD090; width:100%"/></td>
                <td width="15%">Walls</td>
                <td width="35%"><input name="wall_dam_info" value="<?php echo $row['wall_dam_info'];?>" style="background-color:#FAD090; width:100%"/></td>
            </tr>
            <tr>
            	<td width="15%">Windows</td>
                <td width="35%"><input name="window_dam_info" value="<?php echo $row['window_dam_info'];?>" style="background-color:#FAD090; width:100%"/></td>
                <td width="15%">Content</td>
                <td width="35%"><input name="content_dam_info" value="<?php echo $row['content_dam_info'];?>" style="background-color:#FAD090; width:100%"/></td>
            </tr>  
          	<tr>
            	<td colspan="4">Other <input type="text" style="background-color:#FAD090" name="other_dam" id="other_dam" size="30" value="<?php echo $row['other_dam'];?>"/></td>
          	</tr>
            </table>
          </td>
        	
             <td class="middle-left-child" width="<?php echo $col1;?>" ></td>
          <td class="middle-right-child" width="<?php echo $col2;?>"> 
			 	</td>
        </tr>
        <tr>
        	<td class="middle-child" colspan="2">&nbsp;</td>
             <td class="middle-left-child" width="<?php echo $col1;?>" >Other Insurance Info</td>
             <td class="middle-right-child" width="<?php echo $col2;?>"><textarea name="other_ins_info" cols="30" rows="4" style="background-color:#FAD090; font-weight:bold; font-size:14px"><?php echo stripcslashes($row['other_ins_info']);?></textarea></td>
        </tr>
        <tr>
        	 <td class="middle-left-child" width="<?php echo $col1;?>">Attendee:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <?php if ( ($_SESSION['user_level'] >= RR_LEVEL && $row['job']!=11) || $_SESSION['user_level'] >= RR_LEVEL){
			?>
            <select name="attendee" style="background-color:#FAD090">
                    <?php
						$tp = 0;
						$aid = $row['attendee_id'];
						if($aid==0){
							echo '<option selected="selected" value="0"></option>';	
						}
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
            	<input readonly type="text" value="<?php
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
           	<td class="middle-left-child" width="<?php echo $col1;?>"></td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            </td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $row['requestedBy'];?>" style="background-color:#FAD090  <?php if(strlen(trim( $row['requestedBy']))<3) {echo ';border:3px solid #FF0000;';}?>" name="requestedBy" id="requestedBy" size="20" maxlength="50"/> VII: <input type="checkbox" name="c_req" id="c_req" onchange="copyName()"/></td>
        	 <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Add. Phone:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $row['AddPhone'];?>" style="background-color:#FAD090; <?php if(strlen(trim( $row['AddPhone']))<7 && $row['AddPhone']!=='191') {echo ';border:3px solid #FF0000;';}?>" name="addphone" id="addphone" size="18"/ >  VII: <input type="checkbox" name="c_mob" id="c_mob" onchange="copyPhone()"/></td>
           	<td class="bottom-child" colspan="2">&nbsp;</td>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Other Contact:</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="other_contact" id="other_contact" size="20" value="<?php echo $row['other_contact'];?>"/>
         	</td>  
            <td class="top-child_h" colspan="2" style="color:#148540" align="center">&nbsp;</td>
      	</tr>
        <tr>
        	
            	<td class="middle-left-child" width="<?php echo $col1;?>">Phone:</td>
          	  	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo  $row['other_contact_phone'];?>" name="other_contact_phone"  id="other_contact_phone" style="background-color:#FAD090;" size="20" />
                </td>
                <td class="middle-child" colspan="2" width="<?php echo $col1;?>">Action Taken to mitigate risk?</td>
            
            </td>
			
        </tr> 
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"></td>
            <td class="middle-left-child" width="<?php echo $col1;?>"></td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            <textarea name="mit_risk_info" cols="30" rows="4" style="background-color:#FAD090;"><?php echo stripcslashes($row['mit_risk_info']);?></textarea></td>
        </tr>  
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Status:</td>
        	<td class="middle-right-child" width="<?php echo $col2;?>"><?php 
				if( (($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] > POWER_LEVEL) ) ){
					
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
            <input name="statusd" style="background-color:#FAD090" readonly value="<?php
				$sql2 = "SELECT * FROM status WHERE id = '$row[status]'";
				$rs2 = mysql_query($sql2);
				$row2 = mysql_fetch_array($rs2);
            	echo $row2['status'];
			?>"/>
            
            <?php 
			}
			if($row['status']==19){ //missing information
				if($row['mi']){
					echo ' MI: <input type="checkbox" name="mi" checked="checked"/>';	
				}
				else{
					echo ' MI: <input type="checkbox" name="mi"/>';
				}
			}
			?>
            </td>
              <td class="middle-left-child" width="<?php echo $col1;?>">Client Info</td>
             <td class="middle-right-child" width="<?php echo $col2;?>"><textarea name="client_note" cols="30" rows="4" style="background-color:#FAD090;"><?php echo stripcslashes($row['client_note']);?></textarea>
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
            <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
             <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
             </td>          
             <?php } ?>    
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Closed:</td>
        	<td class="middle-right-child" width="<?php echo $col2;?>">
            	<input type="text" id="closeddt" name="closeddt" readonly value="<?php echo $row['closedt'];?>"/>
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
              <?php if ($_SESSION['user_level']>1){?>
        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
            	          
            </td>
            <?php } ?>
        </tr>
         <tr>
         	<td colspan="2" class="bottom-child">&nbsp;</td>
             <?php if ($_SESSION['user_level']>1){?>
             <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
            
            </td>
            <?php } ?>
      	</tr>
        <tr>
        	<td class="top-child_h" colspan="2" style="color:#148540" align="center">Job Information</td>
              <?php if ($_SESSION['user_level']>1){?>
        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
            	
            </td>
            <?php } ?>
        </tr>
        <tr>
        <?php 
				if($row['job']==7 || $row['job']==8 || $row['job']==18 || $row['job']==23 || $row['job']==28 || $row['job'] == 15 ){
			?>
        	<td class="middle-left-child" colspan="1">Accident Info:</td>
            <td class="middle-right-child" colspan="1"> 
            	<div class="buttonwrapper">
					<a class="squarebutton" target="_blank" href="accident_extra.php?sc=<?php echo $id?>"><span>Extra Information</span></a>
				</div>
           </td>
            <?php } 
			else if($row['job']==2){ //flat Tires
			?>
            	<td class="middle-left-child" colspan="1">Spare Tire Available:</td>
            	 <td class="middle-right-child" width="<?php echo $col2;?>">
           		<select name="spare" style="background-color:#FAD090">
                	<option value="1" <?php if($row['spare']==1){echo 'selected="selected"';}?>>Yes</option>
                    <option value="0" <?php if($row['tools']==0){echo 'selected="selected"';}?>>No</option>
                </select>
                </td>
             <?php }
			 else if($row['job']==21){ //Maintenance
			?>
            	<td class="middle-left-child" colspan="1">Type:</td>
            	 <td class="middle-right-child" width="<?php echo $col2;?>">
           		<select name="ms_type" style="background-color:#FAD090">
                	<option value="0" <?php if($row['ms_type']==0){echo 'selected="selected"';}?>></option>
                    <?php
						$sql11="SELECT * FROM `vehicle_maintenance_type`";
						$rs11=mysql_query($sql11);
						while($row11=mysql_fetch_array($rs11)){
							if($row['ms_type']==$row11['id']){
								echo '<option value="'.$row11['id'].'" selected="selected">'.$row11['type'].'</option>';
							}
							else{
								echo '<option value="'.$row11['id'].'">'.$row11['type'].'</option>';
							}
						}
					?>
                </select>
                </td>
             <?php }
			 else if($row['job']==24){ //Service
			?>
            	<td class="middle-left-child" colspan="1">Type:</td>
            	 <td class="middle-right-child" width="<?php echo $col2;?>">
           		<select name="ms_type" style="background-color:#FAD090">
                	<option value="0" <?php if($row['ms_type']==0){echo 'selected="selected"';}?>></option>
                    <?php
						$sql11="SELECT * FROM `vehicle_service_type`";
						$rs11=mysql_query($sql11);
						while($row11=mysql_fetch_array($rs11)){
							if($row['ms_type']==$row11['id']){
								echo '<option value="'.$row11['id'].'" selected="selected">'.$row11['type'].'</option>';
							}
							else{
								echo '<option value="'.$row11['id'].'">'.$row11['type'].'</option>';
							}
						}
					?>
                </select>
                </td>
             <?php }
			 else{
			?>
            	<td class="middle-left-child" colspan="1">&nbsp;</td>
                <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
            <?php 
			 }
			 if ($_SESSION['user_level']>1){?>
             
            <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
            
            </td>
            <?php } ?>
                  
            
      	</tr>
        <tr>
        	<?php if($row['job']==7 || $row['job']==8 || $row['job']==18 || $row['job']==23 || $row['job']==28 || $row['job'] == 15){ ?>
         	<td class="middle-left-child" width="<?php echo $col1;?>">Claim No.:<br/>Handler:<br/>Police Rep:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" style="background-color:#FAD090 <?php if(trim($row['claimNo'])==='') {echo ';border:3px solid #FF0000;';}?>" name="claimNo" size="20" value="<?php echo $row['claimNo'];?>"/>
            <br/>
            <select style="background-color:#FAD090 <?php if($row['claimsAttId']==0) {echo ';border:3px solid #FF0000;';}?>" name="claimsAtt">
            	<option value="0"></option>
            	<?php
					$sql12 = "SELECT * FROM rental_request WHERE active=1";
					$rs12 = mysql_query($sql12);
					while($row12=mysql_fetch_array($rs12)){
						if($row['claimsAttId']==$row12['id']){
							echo '<option selected="selected" value="'.$row12['id'].'">'.$row12['name'].'</option>';
						}
						else{
							echo '<option value="'.$row12['id'].'">'.$row12['name'].'</option>';	
						}
					}
					
				?>
            </select>
            <br/>
            Request&nbsp;<input  type="checkbox" style="background-color:#FAD090" name="police_rep_req" id="police_rep_req" <?php if(trim($row['police_report_req'])!==''){echo 'checked="checked"';} ?>/>&nbsp;Received&nbsp;<input  type="checkbox" style="background-color:#FAD090" name="police_rep_rec" id="police_rep_rec" <?php if(trim($row['police_report_rec'])!==''){echo 'checked="checked"';} ?>/>
            </td> <?php } 
			else if($row['job']==2){ //flat Tires
			?>
            	<td class="middle-left-child" width="<?php echo $col1;?>">Tools:</td>
                <td class="middle-right-child" width="<?php echo $col2;?>">
           		<select name="tools" style="background-color:#FAD090">
                	<option value="1" <?php if($row['tools']==1){echo 'selected="selected"';}?>>Yes</option>
                    <option value="0" <?php if($row['tools']==0){echo 'selected="selected"';}?>>No</option>
                </select>
                </td>
            <?php	
			}
			else if($row['job']==21 || $row['job']==24){ //Maintenance or service
			?>
            	<td class="middle-left-child" width="<?php echo $col1;?>">KM / Cost:</td>
                <td class="middle-right-child" width="<?php echo $col2;?>">
           		<input style="background-color:#FAD090;" type="text" size="10" name="km" id="km" value="<?php echo $row['km'];?>"/> / <input type="text" style="background-color:#FAD090;" size="10" name="cost" id="cost" value="<?php echo number_format($row['cost'],2);?>"/>
                </td>
            <?php	
			}
			else{?>
        	<td class="middle-child" colspan="2">&nbsp;</td>
            <?php } ?>
             <?php if ($_SESSION['user_level']>1){?>
            <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
           	
            </td>
            <?php } ?>
           
             
        </tr>
        <tr>
        	<td class="bottom-left-child" width="<?php echo $col1;?>">
            	<?php
					if($row['job']==2){ //flat tire
						echo 'No. Flat Tires:';
					}
				?>
            </td>
           	<td class="bottom-right-child" width="<?php echo $col2;?>">
            	<?php
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
				?>
            </td>
            <td class="bottom-left-child" width="<?php echo $col1;?>"></td>
            <td class="bottom-right-child" width="<?php echo $col2;?>"></td>
        </tr>
        <tr>
        	<td class="top-child_h" colspan="4" style="color:#148540" align="center">Comments</td>
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
        	<td class="top-left-child" width="<?php echo $col1;?>"></td>
            <td class="top-right-child" width="<?php echo $col2;?>" colspan="3"><select name="tow_reason" id="tow_reason" style="background-color:#FAD090; <?php if($row['job'] != 3 && $row['job'] != 12 && $row['job'] != 13 && $row['job'] != 14  && $row['job'] != 16  && $row['job'] != 19  && $row['job'] != 20  && $row['job'] != 22 && $row['job'] != 32 && $row['job'] != 33 && $row['job'] != 36 && $row['job'] != 29){ echo 'display:none'; }?>">
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
       		<td class="bottom-right-child" width="<?php echo $col2;?>" colspan="3"><textarea required name="notes" cols="56" rows="4" style="background-color:#FAD090; font-weight:bold; font-size:14px"><?php echo stripcslashes($row['notes']);?></textarea></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">Picture/PDF:</td>
         	<td colspan="3"><input name="image_upload_box" type="file" id="image_upload_box" size="40" /></td>
         </td>
        <?php 
			if($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] >= POWER_LEVEL){
		?>
       	<tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td colspan="5" cellspacing="0">
            	<table width="100%" cellspacing="0">
                	<tr cellspacing="0">
                    	<td class="left-child" width="14%" valign="top" cellspacing="0">&nbsp;
                        	    
                        </td>
                          <td class="middle-full-child" width="14%" valign="top">&nbsp;
                    	
                    </td>
                     <td class="middle-full-child" width="14%" valign="top">&nbsp;
                    	
                    </td>
                    	<td class="middle-full-child" valign="top" cellspacing="0"> 
                        
                        <?php 
						//survey 43
						if($_SESSION['user_level'] >= POWER_LEVEL && ($row['job']==7 || $row['job']==8 || $row['job']==28 || $row['job']==15 || $row['job']==18) && !$sid) { ?>
       					<input type="submit" name="survey" value="Survey" style="font-size:14px; font-weight:bold; width:85px;"/>
                        &nbsp;
        				<?php }
						else{?>
                        	&nbsp;
                        <?php }
						?>
                        
                       
                       	<?php if($_SESSION['user_level'] >= POWER_LEVEL && ($row['job']==7 || $row['job']==8 || $row['job']==28 || $row['job']==15 || $row['job']==18)) { ?>
       					<input type="submit" name="rental" value="Rental" style="font-size:14px; font-weight:bold; width:85px;"/>
                        &nbsp;
        				<?php }
						else{?>
                        	&nbsp;
                        <?php }
						if($_SESSION['user_level'] >= POWER_LEVEL){?>
                        <input type="submit" name="delete" value="Delete" style="font-size:14px; font-weight:bold; width:85px;"/>
                             &nbsp;
        					<?php } 
							else { echo "&nbsp;";}?>
                           
                        <?php if($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] >= POWER_LEVEL){
						?>
                       	  <input type="submit" name="submit" value="Submit" style="font-size:14px; font-weight:bold; width:85px;"/>
                        <?php } 
						else { echo "&nbsp;";}?>                         
						<?php if($_SESSION['user_level'] >= POWER_LEVEL && ($row['job']==7 || $row['job']==8 || $row['job']==15) || $row['job']==18){?>
                        <br/><br/>&nbsp;
                        <input type="submit" name="notify_broker" value="Notify Brk" style="font-size:14px; font-weight:bold; width:85px;"/>
                             &nbsp;
        					<?php } 
							else { echo "&nbsp;";}?>
                        <?php if($_SESSION['user_level'] >= POWER_LEVEL && ($row['job']==7 || $row['job']==8 || $row['job']==15) || $row['job']==18){?>
                        <input type="submit" name="wreck_sale" value="Wreck Sale" style="font-size:14px; font-weight:bold; width:85px;"/>
                             &nbsp;
        					<?php } 
							else { echo "&nbsp;";}?>
                             <?php if($_SESSION['user_level'] >= POWER_LEVEL && ($row['job']==7 || $row['job']==8 || $row['job']==15) || $row['job']==18){?>
                        <input type="submit" name="parts_sale" value="Parts Sale" style="font-size:14px; font-weight:bold; width:85px;"/>
                             &nbsp;
        					<?php } 
							else { echo "&nbsp;";}?>
                        </td>
                        <td class="right-child" width="14%" valign="top">&nbsp; </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td colspan="5">
        	<table width="100%" cellspacing="0">
        <?php
			}
		?>
        
        <?php
			if($_SESSION['user_level'] < POWER_LEVEL){
		?>
        	<tr><td colspan="5">&nbsp;</td></tr>
            <tr><td colspan="5">
            	<table width="100%" cellspacing="0" cellpadding="0">
                	<tr>
                    <td class="left-child" width="14%" cellspacing="0">&nbsp;
                   	 
                    </td>
                    <td class="middle-full-child" width="14%" valign="top">&nbsp;
                   	  
                    </td>
                    <td class="middle-full-child" width="14%" valign="top">&nbsp;
                   	  
                    </td>
                    <td <?php if($_SESSION['user_level'] == RR_LEVEL){echo 'class="middle-full-child"';} else{echo 'class="right-child"';}?> valign="top" cellspacing="0">
                    	&nbsp;
                    	 <?php if(1){ ?>
       				  <input type="submit" name="tow" value="Tow" style="font-size:14px; font-weight:bold;"/>
        				<?php } ?>
                        &nbsp;
                        <?php 
						//survey 43
						if($_SESSION['user_level'] >= RR_LEVEL && ($row['job']==7 || $row['job']==8 || $row['job']==28) && !$sid) { ?>
       					<input type="submit" name="survey" value="Survey" style="font-size:14px; font-weight:bold; width:100px;"/>
                        &nbsp;
        				<?php }
						else{?>
                        	&nbsp;
                        <?php }
						?>
                        <?php if($row['job']==7 || $row['job']==8 || $row['job']==28) { ?>
       				  <input type="submit" name="rental" value="Rental" style="font-size:14px; font-weight:bold;"/>
        				<?php } ?>
                    	&nbsp;
                        <?php if($row['status'] !=1){?>
       				  <input type="submit" name="submit" value="Submit" style="font-size:14px; font-weight:bold;"/>
           				<?php } ?> 
                    </td>
                    <?php
						if($_SESSION['user_level'] == RR_LEVEL){
					?>
                    	<td class="right-child" width="14%" valign="top">&nbsp;</td>

                    <?php
						}
					?>
                    </tr>
                </table>
            </td></tr>
        <?php } ?>
        <tr><td colspan="5">&nbsp;</td></tr> 
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td class="child" colspan="5"><table width="100%" cellspacing="0">
        <?php
			$i=1;
			$dirname = FOLDER."gcimage/".$id;
			$thumbs = FOLDER."gcthumbs/".$id;
			$docs = FOLDER."gcdocs/".$id;
			$docst= FOLDER."gcdocsthumbs/".$id;
			$movs= FOLDER."gcmov/".$id;
			$documents = scandir($docs);
			$images = scandir($dirname);
			$movies=scandir($movs);
			$ignore = Array(".", "..");
			$n = 1;
			$r = 1;
			
			$sql22 = "SELECT * FROM service_gc WHERE id = '$id'";
			$rs22 = mysql_query($sql22);
			$row22 = mysql_fetch_array($rs22);
			
			foreach($documents as $doc){
						
				
				if(!in_array($doc,$ignore)){
					if($n == 1){
						echo ' <tr><td colspan="4" style="color:#148540">Document(s)</td></tr>';
						$n = 0;	
					}
				if(file_exists($docst= FOLDER."gcdocsthumbs/".$id)){
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
					
					
					
					$r++;
				}
				else{
					echo '<tr><td colspan="4"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'">'.$doc.'</a></td></tr>';
				}
				
				
				}
				
			}
			if(is_dir(FOLDER.'gcdocs/'.$id)){
				echo '<tr><td colspan="4">&nbsp;</td></tr>
				<tr><td colspan="4"><a style="color:#DCB272" href="zip_download.php?dir=gcdocs/'.$id.'">Download All Document(s)</a></td></tr>';
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
					$i++;
					if($_SESSION['user_level'] >= POWER_LEVEL && $r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($_SESSION['user_level'] >= POWER_LEVEL){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($_SESSION['user_level'] >= RR_LEVEL && $r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a></td>';
					}
					else if($_SESSION['user_level'] >= RR_LEVEL){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a></td>';
					}
					else if($r==1){
						echo '<td width="25%"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
					}
					else{
						echo '<td width="25%"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
					}
					
					if($r==4){
						echo '</tr>';
						$r = 0;	
					}
					$r++;
				};
			} 
			echo '<input type="hidden" name="eam" value="'.$i.'" />';
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
		
		//Begin Video
		?>
        <?php			
			$n = 1;
			foreach($movies as $curmov){
				if(!in_array($curmov, $ignore)) {
					if($n == 1){
						echo ' <tr><td colspan="4" style="color:#148540">Video(s)</td></tr>';
						$n = 0;	
					}
					if($r==1){
						echo'<tr>';	
					}
					
					if($_SESSION['user_level'] >= POWER_LEVEL && $r==1){
						echo '<td width="25%"><video controls width="275" height="250" name="" src="'.$movs.'/'.$curmov.'"></video><br/><a style="color:#DCB272" href="delete.php?file='.$movs.'/'.$curmov.'&sc='.$id.'">Delete</a></td>';
					}
					else if($_SESSION['user_level'] >= POWER_LEVEL){
						echo '<td width="25%"><video controls width="275" height="250" name="" src="'.$movs.'/'.$curmov.'"></video><br/><a style="color:#DCB272" href="delete.php?file='.$movs.'/'.$curmov.'&sc='.$id.'">Delete</a></td>';
					}
					else if($_SESSION['user_level'] >= RR_LEVEL && $r==1){
						echo '<td width="25%"><video controls width="275" height="250" name="" src="'.$movs.'/'.$curmov.'"></video></td>';
					}
					else if($_SESSION['user_level'] >= RR_LEVEL){
						echo '<td width="25%"><video controls width="275" height="250" name="" src="'.$movs.'/'.$curmov.'"></video></td>';
					}
					else if($r==1){
						echo '<td width="25%"><video controls width="275" height="250" name="" src="'.$movs.'/'.$curmov.'"></video></td>';
					}
					else{
						echo '<td width="25%"><video controls width="275" height="250" name="" src="'.$movs.'/'.$curmov.'"></video></td>';
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

			if (is_dir(FOLDER.'gcimage/'.$id) && $_SESSION['user_level'] >= RR_LEVEL) {
				echo '<a style="color:#DCB272" href="zip_download.php?dir=gcimage/'.$id.'">Download All Image(s)</a>';
			}
			?></td></tr>            
            </table>
      		
       	<?php if($master_sc && $jid != 3 && $jid != 29 && $jid != 12 && $jid != 33){ ?>
        <tr><td colspan="5">&nbsp;</td></tr> 
        
        <tr><td class="child" colspan="5"><table width="100%" cellspacing="0">
        <?php
			
			$id = $master_sc;
			$dirname = FOLDER."gcimage/".$id;
			$thumbs = FOLDER."gcthumbs/".$id;
			$docs = FOLDER."gcdocs/".$id;
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
				if(file_exists($docst= FOLDER."gcdocsthumbs/".$id)){
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
					echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a></td>';	
					}
					else{
						echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a></td></tr>';
					}
					
					$r++;
				}
				}
			}
			if(is_dir(FOLDER.'gcdocs/'.$id)){
				echo '<tr><td colspan="4">&nbsp;</td></tr>
				<tr><td colspan="4"><a style="color:#DCB272" href="zip_download.php?dir='.FOLDER.'gcdocs/'.$id.'">Download All Document(s)</a></td></tr>';
			}
		?>
        <tr><td colspan="4">&nbsp;</td></tr>
        <?php			
			$n = 1;
			$i=0;
			foreach($images as $curimg){
				$i++;
				if(!in_array($curimg, $ignore)) {
					if($n == 1){
						echo ' <tr><td colspan="4" style="color:#148540">Image(s)</td></tr>';
						$n = 0;	
					}
					if($r==1){
						echo'<tr>';	
					}
					
					if($_SESSION['user_level'] >= RR_LEVEL && $r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($_SESSION['user_level'] >= RR_LEVEL){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($r==1){
						echo '<td width="25%"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
					}
					else{
						echo '<td width="25%"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
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

			if (is_dir(FOLDER.'gcimage/'.$id) && $_SESSION['user_level'] >= RR_LEVEL) {
				echo '<a style="color:#DCB272" href="zip_download.php?dir='.FOLDER.'gcimage/'.$id.'">Download All Image(s)</a>';
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
<?php if($_SESSION['user_level'] < POWER_LEVEL){?>
<script  type="text/javascript">
 var frmvalidator = new Validator("edit_sc");
 <?php if($row['job'] == 7 && !is_dir(FOLDER.'gcimage/'.$id)){ ?>
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