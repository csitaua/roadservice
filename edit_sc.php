<?php



//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include $_SERVER["DOCUMENT_ROOT"] .'/dbc.php';

date_default_timezone_set('America/Aruba');

page_protect();

include $_SERVER["DOCUMENT_ROOT"] ."/support/function.php";

session_start();

$sql4 = "SELECT * FROM users WHERE id='".$_SESSION['user_id']."'";
$rs4 = $db2->query($sql4);
$row4 = $rs4->fetch_assoc();

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

if($_REQUEST['message']==1){

	//file("https://roadservice.nagico-abc.com/roadservice/whatsapi/src/sendcall.php?id=".$id);

}



echo menu();





$col1 = 85;

$col2 = 200;

$history_item = 3;

$sql = "SELECT * FROM service_req WHERE id = '$id'";
$rs = $db2->query($sql);
$row = $rs->fetch_assoc();

$st ='';

if($_SESSION['user_level'] < POWER_LEVEL){

	$st='readonly="readonly"';

}





$sql2 = "SELECT COUNT(*) as t FROM VW_VEHICLE WHERE VehStatus='A'";
$rs2=mssql_query($sql2);
$row2 = mssql_fetch_array($rs2);



//$rs2=$inspro->query($sql2);

//$row2=$rs2->fetch(PDO::FETCH_ASSOC);



?>





<form name="edit_sc" enctype="multipart/form-data" action="rec_sc.php?sc=<?php echo $id;?>" method="post">

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

        	<td class="middle-left-child" colspan="3">Time Inserted: <?php echo substr($row['timestamp'],0,-3);?> &nbsp; <span style="color:blue">Secondary</span>

					<?php if($_SESSION['user_level'] >=4){?>&nbsp;<?php } ?></td>

       		<td class="middle-right-child" colspan="2">Time Review: <?php

				if($row['pendingReviewID']!=0){

					$t1 = $row['timestamp'];

					$t2 =  $row['pendingReviewTime'];



					list($date,$time) = explode(' ',$t1);

					list($month,$day,$year) = explode('-',$date);

					$d1=new datetime($year."-".$month."-".$day." ".$time);



					list($date,$time) = explode(' ',$t2);

					list($month,$day,$year) = explode('-',$date);



					$d2=new datetime($year."-".$month."-".$day." ".$time);

					$int=$d1->diff($d2);



					echo $row['pendingReviewTime']." By: ".getUserFNameID($row['pendingReviewID'])." ".$int->format('%H:%I');

				}

			?></td>

        <?php

		}

		else{

		?>

        <tr>

        	<td class="top-left-child" colspan="3">Time Inserted: <?php echo substr($row['timestamp'],0,-3);?> <span style="color:blue">Primary</span>

					<?php if($_SESSION['user_level'] >=4){?>&nbsp;<?php } ?>

            </td>

            <td class="top-right-child" colspan="2">Time Review: <?php

			if($row['pendingReviewID']!=0){

				$t1 = $row['timestamp'];

				$t2 =  $row['pendingReviewTime'];



				list($date,$time) = explode(' ',$t1);

				list($month,$day,$year) = explode('-',$date);

				$d1=new datetime($year."-".$month."-".$day." ".$time);



				list($date,$time) = explode(' ',$t2);

				list($month,$day,$year) = explode('-',$date);



				$d2=new datetime($year."-".$month."-".$day." ".$time);

				$int=$d1->diff($d2);



				echo $row['pendingReviewTime']." By: ".getUserFNameID($row['pendingReviewID'])." ".$int->format('%H:%I');



			}?>





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

			$path=FOLDER.'rranotification/'.$id.'/';

			if( file_exists($path.str_replace('/','-',$row['pol']).'_Acc_Notification.pdf')) {

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

            	<td class="middle-child" colspan="5">Time Requested:

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

               	colspan="5">Time Arrival:

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

        <tr>

        	<td colspan="4" class="top-child_h" style="color:#148540" align="center">Service Call Missing Check List</td>

            <td colspan="1" class="top-child_h" style="color:#148540" align="center">Nagico Rating</td>

       	</tr>

        <tr>

        	<?php $sql3 = "SELECT * FROM service_req_rating WHERE service_req_id='$id'";

					$rs3=mysql_query($sql3);

					$row3=mysql_fetch_array($rs3);

			?>

        	<td class="middle-child" colspan="4">Call/V.I. Info <input type="checkbox" style="background-color:#FAD090" name="call" <?php if($row3['call']==1){ echo 'checked="checked"';}?>/>&nbsp;&nbsp;Notes/Remarks <input type="checkbox" style="background-color:#FAD090" name="notes_checked" <?php if($row3['notes']==1){ echo 'checked="checked"';} ?>/>&nbsp;&nbsp;Pictures/PDF <input type="checkbox" style="background-color:#FAD090" name="pictures" <?php if($row3['pictures']==1){ echo 'checked="checked"';} ?>/>&nbsp;&nbsp;Administration <input type="checkbox" style="background-color:#FAD090" name="adm" <?php if($row3['adm']==1){ echo 'checked="checked"';} ?>/>&nbsp;&nbsp;Pers. No. <input type="checkbox" style="background-color:#FAD090" name="c_pers_no" <?php if($row3['pers_no']==1){ echo 'checked="checked"';} ?>/>&nbsp;&nbsp;VIN <input type="checkbox" style="background-color:#FAD090" name="vin_no" <?php if($row3['vin_no']==1){ echo 'checked="checked"';} ?>/></td>

          <td class="middle-child" colspan="1">

          <table width="100%">

          <tr><td width="25%">Sales Rating</td><td width="25%"><select name="sales_rating" style="background-color:#FAD090">

                    <?php

						$call = $row3['sales_rating'];

						$sql4 = "SELECT * FROM service_rating";

						$rs4 = mysql_query($sql4);

						while($row4=mysql_fetch_array($rs4)){

							if($call == $row4['id']){

								echo '<option selected="selected" value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

							else{

								echo '<option value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

						}

					?>

              </select></td>

              <td width="25%">Call Rating</td><td width="25%"><select name="call_rating" style="background-color:#FAD090">

                    <?php

						$call = $row3['call_rating'];

						$sql4 = "SELECT * FROM service_rating";

						$rs4 = mysql_query($sql4);

						while($row4=mysql_fetch_array($rs4)){

							if($call == $row4['id']){

								echo '<option selected="selected" value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

							else{

								echo '<option value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

						}

					?>

              </select></td></tr></table></td>

       	</tr>

         <tr>

        	<td class="bottom-child" colspan="4">Job Info <input type="checkbox" style="background-color:#FAD090" name="job_info" <?php if($row3['job_info']==1){ echo 'checked="checked"';}?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Driver License <input type="checkbox" style="background-color:#FAD090" name="driver_license" <?php if($row3['driver_license']==1){ echo 'checked="checked"';} ?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Couple Acc. <input type="checkbox" style="background-color:#FAD090" name="couple_acc" <?php if($row3['couple_acc']==1){ echo 'checked="checked"';} ?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Claims Form <input type="checkbox" style="background-color:#FAD090" name="claims_form" <?php if($row3['claims_form']==1){ echo 'checked="checked"';} ?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Email <input type="checkbox" style="background-color:#FAD090" name="email" <?php if($row3['email']==1){ echo 'checked="checked"';} ?>/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="roadservice_rating" style="background-color:#FAD090">

                    <?php

						$call = $row3['roadservice_rating'];

						$sql4 = "SELECT * FROM service_rating";

						$rs4 = mysql_query($sql4);

						while($row4=mysql_fetch_array($rs4)){

							if($call == $row4['id']){

								echo '<option selected="selected" value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

							else{

								echo '<option value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

						}

					?>

              </select></td>

            <td class="bottom-child" colspan="1">

             <table width="100%">

          <tr><td width="25%">Claims Rating</td><td width="25%"><select name="claims_rating" style="background-color:#FAD090">

                    <?php

						$call = $row3['claims_rating'];

						$sql4 = "SELECT * FROM service_rating";

						$rs4 = mysql_query($sql4);

						while($row4=mysql_fetch_array($rs4)){

							if($call == $row4['id']){

								echo '<option selected="selected" value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

							else{

								echo '<option value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

						}

					?>

              </select></td><td width="25%">Tow Rating</td><td width="25%"</td><select name="tow_rating" style="background-color:#FAD090">

                    <?php

						$call = $row3['towing_rating'];

						$sql4 = "SELECT * FROM service_rating";

						$rs4 = mysql_query($sql4);

						while($row4=mysql_fetch_array($rs4)){

							if($call == $row4['id']){

								echo '<option selected="selected" value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

							else{

								echo '<option value="'.$row4['id'].'">'.$row4['description'].'</option>';

							}

						}

					?>

              </select></td></tr></table></td>

       	</tr>

         <tr><td colspan="5">&nbsp;</td></tr>

        <tr>

        	<td colspan="2" class="top-child_h" style="color:#148540" align="center">Call Information</td>

            <td colspan="2" class="top-child_h" style="color:#148540" align="center">Vehicle Insurance Information</td>

            <td rowspan="25" valign="top">

            <table width="100%" cellspacing="0">

            	<tr><td class="top-child_h" colspan="2" style="color:#148540" align="center">Vehicle Insurance Information</td></tr>

            	<tr><td class="middle-child" colspan="2">&nbsp;</td></tr>

                <?php

				$lic = $row['a_number'];

				$policy = $row['pol'];

				$use_extra_empty=0;

				$use_extra = 0;

				//$sql2 = "SELECT * FROM vehicles_2 WHERE LicPlateNo = '$lic' ORDER BY STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";

				/*$sql2 = "SELECT * FROM vehicles_2 WHERE PolicyNo LIKE '$policy' AND `LicPlateNo` = '$lic' ORDER BY Status DESC, STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";

				$rs2 = mysql_query($sql2);*/

				$sql2 = "SELECT Full_Name, ClientNo, Status, VehStatus, Address1, Date_Application, VehDate_Effective, VehDate_Renewal, YearMake, Color, BodyType, Seats, VehicleValue, VehCoverage, VehUse, WorkPhone, HomePhone, MobilePhone, AgentName, Premium, PolicyFee, Deduct  FROM VW_VEHICLE WHERE LicPlateNo = '$lic' AND PolicyNo LIKE '$policy' ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Renewal DESC, PolicyNo DESC";

				$params = array();

				//$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

				$rs2 = mssql_query($sql2);



				$sql3 = "SELECT * FROM `non_client_extra` WHERE `id` = '$row[a_number]'";

				$rs3 = mysql_query($sql3);





				if(mssql_num_rows($rs2)!=0 || mysql_num_rows($rs3)!=0){

				//if($rs2->rowCount()!=0 || mysql_num_rows($rs3)!=0){

					$vcol1 = 45;

					$vcol2 = 55;

					if(mssql_num_rows($rs2)!=0 && trim($policy)!==''){

						$row2 = mssql_fetch_array($rs2);

						//$row2=$rs2->fetch(PDO::FETCH_ASSOC);

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

							echo '<a href="'.SP_SITE.$row2['ClientNo'].'" target="_blank">'.$row2['ClientNo'].'</a>';

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

                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php $tdate = new datetime (substr(date("d-M-y", strtotime($row2['Date_Application'])),0,10));

			echo date_format($tdate,"d F, Y");?></td>

                    </tr>

                     <tr>

                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">Car Insured From:</td>

                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php

						if(!$use_extra){

							$fdate = new datetime(substr(date("d-M-y", strtotime($row2['VehDate_Effective'])),0,10));

							$tdate = new datetime(substr(date("d-M-y", strtotime($row2['VehDate_Renewal'])),0,10));

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

							echo $row2['YearMake'].' / '.$row2['Color'];

						}

						else{

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

						}?></td>

                    </tr>

                     <tr>

                    	<td class="middle-left-child" width="<?php echo $vcol1;?>%">New Value / Year Value <?php if(trim($row['manu_date'])!=='' AND trim($row['opendt'])!==''){

						echo ' / Day Value';

						}?>:</td>

                        <td class="middle-right-child" width="<?php echo $vcol2;?>%"><?php

						if(!$use_extra){

							echo number_format($row2['VehicleValue'],2).' / '.number_format(dayValue($row2['YearMake'],$row2['VehicleValue'],$row2['VehUse']),2);

							$vv=$row2['VehicleValue'];

							$vuse=$row2['VehUse'];

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

                <tr><td class="top-child_h"  colspan="2" style="color:#148540" align="center">Drivers License</td></tr>

                 <tr><td class="middle-child"  colspan="2" rowspan="3">

				 Drivers License: <input name="licenseNo" id="licenseNo"  style="background-color:#FAD090" value="<?php echo $row['licenseNo'];?>" size="20" onKeyUp="showUpload()"/>

                 <br/>

                 <br/>

                 Email: <?php

				$sql3 = "SELECT * FROM drivers_license where id = '$row[licenseNo]'";

				$rs3 = mysql_query($sql3);

				$row3 = mysql_fetch_array($rs3);

				echo $row3['email'];

				 ?>

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

            <td class="middle-left-child" width="<?php echo $col1;?>">Car Number:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090 <?php if(strlen(trim( $row['a_number']))<3) {echo ';border:3px solid #FF0000;';}?>" name="num" id="num" size="15" value="<?php echo $row['a_number'];?>"/></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Location:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="loc" id="loc2" size="23" value="<?php echo $row['location'];?>"/>  VII: <input type="checkbox" name="c_add2" id="c_add2" onchange="copyAddress2()"/> </td>

            <td class="middle-left-child" width="<?php echo $col1;?>">Car:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" style="background-color:#FAD090" name="car" size="25" value="<?php echo $row['car'];?>" /></td>

        </tr>

        <tr>

          	<td class="middle-left-child" width="<?php echo $col1;?>"><?php

            	if($row['job']!=7){ echo 'Location to:';}

				else { echo '';}

			?></td>

            <?php

            	$sqlt="SELECT * FROM `jobs` WHERE id=".$row['job'];

				$rst=mysql_query($sqlt);

				$rowt=mysql_fetch_array($rst);

            ?>

          	<td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if(strcmp($row['toloc'],'')!=0){ echo $st;}?> type="text" style="background-color:#FAD090; <?php if($rowt['jobs_group_id']!=5){ echo 'display:none'; }?>" name="toloc" id="loc" size="23" value="<?php echo $row['to_location'];?>"/> VII: <input type="checkbox" name="c_add" id="c_add" onchange="copyAddress()"/>



            </td>

            <td class="middle-left-child" width="<?php echo $col1;?>">Vin:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" style="background-color:#FAD090" name="vin" size="25" value="<?php echo $row['vin'];?>" /></td>

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

                    </select> </td>

            <td class="middle-left-child" width="<?php echo $col1;?>">Engine Nr.:</td>

             <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 3){echo $st;}?> type="text" style="background-color:#FAD090" name="engine" id="engine" size="20" value="<?php echo $row['engine'];?>"/></td>



        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Town:</td>

             <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>

            <td class="middle-left-child" width="<?php echo $col1;?>">Policy Nr.:</td>

             <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" style="background-color:#FAD090" name="pol" id="pol" size="20" value="<?php echo $row['pol'];?>"/> <?php $temp_pol=$row['pol']?></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Street:</td>

             <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>

             <td class="middle-left-child" width="<?php echo $col1;?>">Fuel Type:</td>

             <td class="middle-right-child" width="<?php echo $col2;?>"><select name="fuel" style="background-color:#FAD090">

             <option value="Gasoline" <?php if($row['fuel'] === 'Gasoline'){echo 'selected="selected"';}?> >Gasoline</option>

             <option value="Deisel" <?php if($row['fuel'] === 'Deisel' OR $row['fuel'] === 'Diesel'){echo 'selected="selected"';}?>>Diesel</option>

             <option value="LPG" <?php if($row['fuel'] === 'LPG'){echo 'selected="selected"';}?>>LPG</option>

             <option value="Electric" <?php if($row['fuel'] === 'Electric'){echo 'selected="selected"';}?>>Electric</option>

             <option value="Hybrid" <?php if($row['fuel'] === 'Hybrid'){echo 'selected="selected"';}?>>Hybrid</option>

             </select>

             </td>

        </tr>

        <tr>

        	<?php

				if($row['job']==7 || $row['job']==8 || $row['job']==18 || $row['job']==23 || $row['job']==28 || $row['job'] == 15 || $row['job']==57){

			?>

            	<td class="middle-left-child" width="<?php echo $col1;?>">Veh Location:</td>

             	<td class="middle-right-child" width="<?php echo $col2;?>">

                <select name="veh_park_loc" style="background-color:#FAD090">

             <option value="" <?php if($row['veh_park_loc'] === ''){echo 'selected="selected"';}?> ></option>

             <option value="Gilbert Home" <?php if($row['veh_park_loc'] === 'Gilbert Home'){echo 'selected="selected"';}?> >Gilbert Home</option>

             <option value="Mark Home" <?php if($row['veh_park_loc'] === 'Mark Home'){echo 'selected="selected"';}?>>Mark Home</option>

             <option value="Client Address" <?php if($row['veh_park_loc'] === 'Client Address'){echo 'selected="selected"';}?>>Client Address</option>

             <option value="Bodyshop" <?php if($row['veh_park_loc'] === 'Bodyshop'){echo 'selected="selected"';}?>>Bodyshop</option>

             <option value="Car Dealer" <?php if($row['veh_park_loc'] === 'Car Dealer'){echo 'selected="selected"';}?>>Car Dealer</option>

             <option value="Police Station" <?php if($row['veh_park_loc'] === 'Police Station'){echo 'selected="selected"';}?>>Police Station</option>

             <option value="Accident Scene" <?php if($row['veh_park_loc'] === 'Accident Scene'){echo 'selected="selected"';}?> >Accident Scene</option>

             </select>

                </td>

            <?php }

			else{

				echo '<td class="middle-child" colspan="2">&nbsp;</td>';

			}

			?>



             <td class="middle-left-child" width="<?php echo $col1;?>" >Transmission:</td>

             <td class="middle-right-child" width="<?php echo $col2;?>"><select name="transmission2" style="background-color:#FAD090">

             <option value="" <?php if($row['transmission'] === ''){echo 'selected="selected"';}?> ></option>

             <option value="Automatic" <?php if($row['transmission'] === 'Automatic'){echo 'selected="selected"';}?> >Automatic</option>

             <option value="Manual" <?php if($row['transmission'] === 'Manual'){echo 'selected="selected"';}?>>Manual</option>

             </select>

             </td>

        </tr>

        <tr>

        	<td class="middle-child" colspan="2">&nbsp;</td>

             <td class="middle-left-child" width="<?php echo $col1;?>" >Manu Date:</td>

             <td class="middle-right-child" width="<?php echo $col2;?>">

             <input type="text" id="manu_date" name="manu_date" readonly value="<?php echo $row['manu_date'];?>"/>

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

        	 <td class="middle-left-child" width="<?php echo $col1;?>">Attendee:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>">

            <?php if ( ($_SESSION['user_level'] >= RR_LEVEL && $row['job']!=11) || $_SESSION['user_level'] >= RR_LEVEL){

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

			if($_SESSION['user_level'] >= POWER_LEVEL){

				?>

                	&nbsp;NSH: <input type="checkbox" name="over_time" <?php if($row['over_time']) echo 'checked="checked"'; ?>/>

                <?php

			}

			else{

				?>

                	&nbsp;NSH: <?php if($row['over_time']) {echo'Yes';} else {echo 'No';}?>

                <?php

			}

			?>

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

			?>

            </td>

        </tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>

       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $row['requestedBy'];?>" style="background-color:#FAD090  <?php if(strlen(trim( $row['requestedBy']))<3) {echo ';border:3px solid #FF0000;';}?>" name="requestedBy" id="requestedBy" size="20" maxlength="50"/> VII: <input type="checkbox" name="c_req" id="c_req" onchange="copyName()"/></td>

        	 <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>

       		<td class="middle-right-child" width="<?php echo $col2;?>"><div class="buttonwrapper" id="extra_info_not_insured" style=" <?php if($row['insured']){echo 'display:none';} else {echo 'display:inline';} ?>">

					<a class="<?php if($use_extra_empty==1){ echo 'squarebuttonm';} else {echo 'squarebutton';}?>" href="javascript:popacc('not_insured_extra.php?sc=<?php echo $row['a_number']?>&sr=<?php echo $id;?>');"><span>Extra Information</span></a>

				</div></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Add. Phone:</td>

       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $row['AddPhone'];?>" style="background-color:#FAD090; <?php if(strlen(trim( $row['AddPhone']))<7 && $row['AddPhone']!=='191') {echo ';border:3px solid #FF0000;';}?>" name="addphone" id="addphone" size="18"/ >  VII: <input type="checkbox" name="c_mob" id="c_mob" onchange="copyPhone()"/></td>

           	<td class="bottom-child" colspan="2">Right Hand Drv.: <input type="checkbox" style="background-color:#FAD090" name="right" <?php if($row['RightHandDrive']==1){ echo 'checked="checked"';} ?>/></td>

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

                	<a style="color:#DCB272" href="change_charge.php?sc=<?php echo $id?>">Change</a> <?php } ?>

         	</td>

            <td class="top-child_h" colspan="2" style="color:#148540" align="center">Adm/Payment Info</td>

      	</tr>

        <tr>

        	<?php if($tp && $_SESSION['user_level']>3){ ?>

            	<td class="middle-left-child" width="<?php echo $col1;?>">TP Charge:</td>

          	  	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="number" value="<?php echo number_format($row['tpCharged'],2,'.','')?>" name="tpCharged" style="background-color:#FAD090; text-align:right" size="10" maxlength="7"/>Afl.&nbsp;Rec. <input type="checkbox" name="tpChargedReceived" <?php if($row['tpChargedReceived']){echo 'checked="checked"';} ?> /></td>



			<?php }

			if (!$tp && $_SESSION['user_level']>1){

			?>

            	<td class="middle-left-child" width="<?php echo $col1;?>"></td>

       			<td class="middle-right-child" width="<?php echo $col2;?>"></td>

			<?php

        	}

			if ($_SESSION['user_level']>1){?>

            <td class="middle-left-child" width="<?php echo $col1;?>">Receipt:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if(trim($row['receipt'])!=='') {echo $st;}?> type="text" value="<?php echo $row['receipt'];?>" style="background-color:#FAD090 <?php if($row['charged']>0 && strlen(trim($row['receipt']))<2) {echo ';border:3px solid #FF0000;';}?>" name="receipt" size="20"/>

          </td>

             <?php } ?>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Vehicle Present:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input  type="checkbox" id="present"  style="background-color:#FAD090" name="present" <?php if($row['present'] ==1 || !$row['present']){echo 'checked="checked"';}?>/>&nbsp;&nbsp;&nbsp;RS Present:&nbsp;<input type="checkbox" name="rspresent" <?php if($row['rspresent']){echo 'checked="checked"';}?>/>

            </td>

              <?php if ($_SESSION['user_level']>1){?>

             <td class="middle-left-child" width="<?php echo $col1;?>">Invoice #:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090 <?php if($row['charged']>0 && strlen(trim($row['invoice']))<2) {echo ';border:3px solid #FF0000;';}?>" name="invoice" size="20" maxlength="30" value="<?php echo $row['invoice'];?>"/>

            </td>

            <?php } ?>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Status:</td>

        	<td class="middle-right-child" width="<?php echo $col2;?>"><?php

				$allow_change= array(182,181,153); //Josefa, Milouska, Christa

				if( (($row['status']==1 || $row['status']==4 || $row['status']==8 || $_SESSION['user_level'] > POWER_LEVEL  ) || in_array($_SESSION['user_id'],$allow_change) ) || allowAttendeeStatusChange($row['status'])) {



			?><select name="status" id="status" style="background-color:#FAD090">

            	<?php

					$sql2 = "SELECT * FROM `status` WHERE `status_group_id` IN (SELECT `id` FROM `status_group` WHERE `status_group_permission`<=".$_SESSION['user_level'].") OR id=".$row['status']." order by `status` ";

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

              <?php if ($_SESSION['user_level']>1){?>

            <td class="middle-left-child" width="<?php echo $col1;?>">PO Number:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="po_number" size="20" value="<?php echo $row['po_number'];?>"/>

            </td>

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

             </select>&nbsp;<div class="buttonwrapper"><a class="squarebutton" target="_blank" href="print_ind_statement.php?id=<?php echo $id ?>"><span>Statement</span></a></div>

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

        	<td class="middle-left-child" width="<?php echo $col1;?>">Receipt Rec.:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>">

            	<?php

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

                <input size="5" type="text" style="background-color:#FAD090" readonly name="po_received_d" value="<?php

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

				?>

            </td>

            <?php } ?>

        </tr>

         <tr>

         	<td colspan="2" class="bottom-child">&nbsp;</td>

             <?php if ($_SESSION['user_level']>1){?>

             <td class="middle-left-child" width="<?php echo $col1;?>">Subrogation:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><?php

			if($_SESSION['user_level']==5){?><input type="checkbox" name="sponser" <?php if($row['sponser']==1) {echo 'checked="checked"';}?>/> <?php }

			else{

				if($row['sponser']==1){

					echo 'Yes';

				}

				else{

					echo 'No';

				}

			}

			?>

            </td>

            <?php } ?>

      	</tr>

        <tr>

        	<td class="top-child_h" colspan="2" style="color:#148540" align="center">Job Information</td>

              <?php if ($_SESSION['user_level']>1){?>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Money Rec:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>">

            	<?php

					$user_id = $_SESSION['user_id'];

					$sql3 = "SELECT * FROM rights WHERE `user_id`='$user_id' AND `department_id`=1";

					$rs3 = mysql_query($sql3);

					$row3 = mysql_fetch_array($rs3);

					if (mysql_num_rows($rs3) != 0 && $row3['right']==2){

				?>

                	<input  type="checkbox" style="background-color:#FAD090" name="money" id="money" <?php if($row['money_delivered']>0){echo 'checked="checked"';} ?> />&nbsp;

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

                <input size="5" type="text" style="background-color:#FAD090" readonly name="moneyd" value="<?php

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

				?>

            </td>

            <?php } ?>

        </tr>

        <tr>

        <?php

				if(isAcc($row['job'])){

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



            <td class="middle-left-child" width="<?php echo $col1;?>">NRC Acc Fee:</td>

           	<td class="middle-right-child" width="<?php echo $col2;?>">

            <input type="checkbox" name="accfee" id="accfee" <?php if($row['accfee']>0) echo 'checked="checked"';?> />

             <?php

					if($row['accfee']>0){

						$did = $row['accfee'];

						$sql2 = "SELECT * FROM `users` WHERE `id`='$did'";

						$rs2 = mysql_query($sql2);

						$row2 = mysql_fetch_array($rs2);

						echo 'By: '.$row2['full_name'];

					}



				?>

            <div class="buttonwrapper">

            <a class="squarebutton" target="_blank" href="print_ind_statement.php?id=<?php echo $id ?>&type=acc"><span>Invoice</span></a></div>

            </td>

            <?php } ?>





      	</tr>

        <tr>

        	<?php if(isAcc($row['job']) || isPropertyDamage($row['job'])){ ?>

         	<td class="middle-left-child" width="<?php echo $col1;?>">Claim No.:<br/>Handler:<br/>Police Rep:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if($_SESSION['user_level'] < 2){echo $st;}?> type="text" style="background-color:#FAD090 <?php if(trim($row['claimNo'])==='') {echo ';border:3px solid #FF0000;';}?>" name="claimNo" size="20" value="<?php echo $row['claimNo'];?>"/>

            <br/>

            <select style="background-color:#FAD090 <?php if($row['claimsAttId']==0) {echo ';border:3px solid #FF0000;';}?>" name="claimsAtt">

            	<option value="0"></option>

            	<?php

					$sql12 = "SELECT * FROM rental_request WHERE active=1 AND `isclaimsHandler`=1";

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

            <td class="middle-left-child" width="<?php echo $col1;?>">NRS Adj Fee:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>">

           <input type="checkbox" name="adjfee" id="adjfee" <?php if($row['adjfee']>0) echo 'checked="checked"';?>/>

            <?php

					if($row['adjfee']>0){

						$did = $row['adjfee'];

						$sql2 = "SELECT * FROM `users` WHERE `id`='$did'";

						$rs2 = mysql_query($sql2);

						$row2 = mysql_fetch_array($rs2);

						echo 'By: '.$row2['full_name'];

					}



				?>

           <div class="buttonwrapper">

            <a class="squarebutton" target="_blank" href="print_ind_statement.php?id=<?php echo $id ?>&type=adj"><span>Invoice</span></a></div>

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

        	<td class="top-left-child" width="<?php echo $col1;?>">Tow Reason:</td>

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

         </tr>

        <?php

			//if($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] >= POWER_LEVEL){

			if(1){

		?>

       	<tr><td colspan="5">&nbsp;</td></tr>

        <tr>

        	<td colspan="5" cellspacing="0">

            	<table width="100%" cellspacing="0">

                	<tr cellspacing="0">

                    	<td class="left-child" width="14%" valign="top" cellspacing="0">

                            &nbsp;<input type="submit" name="acc_link" value="Couple Accident" style="font-size:14px; font-weight:bold;"/>

                            <br/>

                        	&nbsp;ID: <input type="text" name="accident_id" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link']!=0){echo $row['accident_link'];}?>"/>

                             <br/>

                        <?php if($row['accident_link']!=0) { ?>

       							<div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link']?>"><span>Linked Accident</span></a></div>

                        <?php } ?>

                        </td>

                          <td class="middle-full-child" width="14%" valign="top">

                    	<input type="submit" name="acc_link2" value="Couple Accident 2" style="font-size:14px; font-weight:bold;"/><br/>

                    	ID: <input type="text" readonly name="accident_id2" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link2']!=0){echo $row['accident_link2'];}?>"/>

                         <?php if($row['accident_link2']!=0) { ?>

      					<div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link2']?>"><span>Linked Accident 2</span></a></div>

        				<?php } ?>

                    </td>

                     <td class="middle-full-child" width="14%" valign="top">

                    	<input type="submit" name="acc_link3" value="Couple Accident 3" style="font-size:14px; font-weight:bold;"/><br/>

                    	ID: <input type="text" readonly name="accident_id3" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link3']!=0){echo $row['accident_link3'];}?>"/>

                         <?php if($row['accident_link3']!=0) { ?>

      					<div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link3']?>"><span>Linked Accident 3</span></a></div>

        				<?php } ?>

                    </td>

                    	<td class="middle-full-child" valign="top" cellspacing="0"> <?php if(allowAttendeeStatusChange($row['status']) || $_SESSION['user_level'] >= POWER_LEVEL){	?>

                        	&nbsp;

                       	  <input type="submit" name="tow" value="Tow" style="font-size:14px; font-weight:bold; width:85px;"/>

                            &nbsp;

                        <?php }

							else{?>

                            &nbsp;

                      	<?php } ?>



                        <?php

						//survey 43

						if($_SESSION['user_level'] >= POWER_LEVEL && isAcc($row['job']) && !$sid) { ?>

       					<input type="submit" name="survey" value="Survey" style="font-size:14px; font-weight:bold; width:85px;"/>

                        &nbsp;

        				<?php }

						else{?>

                        	&nbsp;

                        <?php }

						?>





                       	<?php //if($_SESSION['user_level'] >= POWER_LEVEL && ($row['job']==7 || $row['job']==8 || $row['job']==28 || $row['job']==15 || $row['job']==18 || $row['job'] == 56 || $row['job'] == 57)) {

							if( ($row['job']==7 || $row['job']==8 || $row['job']==28 || $row['job']==15 || $row['job']==18 || $row['job'] == 56 || $row['job'] == 57)) { ?>

       					<input type="submit" name="rental" value="Rental" style="font-size:14px; font-weight:bold; width:85px;"/>

                        &nbsp;

        				<?php }

						else{?>

                        	&nbsp;

                        <?php }

						if($_SESSION['user_level'] >= POWER_LEVEL){?>
                     &nbsp;
        					<?php }

							else { echo "&nbsp;";}?>



                        <?php if(allowAttendeeStatusChange($row['status']) || $_SESSION['user_level'] >= POWER_LEVEL || allowJobGroupStatusChange($row['job'])){

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

                        <td class="right-child" width="14%" valign="top">



                        </td>

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

			if($_SESSION['user_level'] < POWER_LEVEL && 0){

		?>

        	<tr><td colspan="5">&nbsp;</td></tr>

            <tr><td colspan="5">

            	<table width="100%" cellspacing="0" cellpadding="0">

                	<tr>

                    <td class="left-child" width="14%" cellspacing="0">

                   	  <input type="submit" name="acc_link" value="Couple Accident" style="font-size:14px; font-weight:bold;"/><br/>

                    	ID: <input type="text" readonly name="accident_id" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link']!=0){echo $row['accident_link'];}?>"/>

                         <?php if($row['accident_link']!=0) { ?>

      					<div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link']?>"><span>Linked Accident</span></a></div>

        				<?php } ?>

                    </td>

                    <td class="middle-full-child" width="14%" valign="top">

                   	  <input type="submit" name="acc_link2" value="Couple Accident 2" style="font-size:14px; font-weight:bold;"/><br/>

                    	ID: <input type="text" readonly name="accident_id2" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link2']!=0){echo $row['accident_link2'];}?>"/>

                         <?php if($row['accident_link2']!=0) { ?>

      					<div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link2']?>"><span>Linked Accident 2</span></a></div>

        				<?php } ?>

                    </td>

                    <td class="middle-full-child" width="14%" valign="top">

                   	  <input type="submit" name="acc_link3" value="Couple Accident 3" style="font-size:14px; font-weight:bold;"/><br/>

                    	ID: <input type="text" readonly name="accident_id3" maxlength="5" size="8" style="background-color:#FAD090" value="<?php if($row['accident_link3']!=0){echo $row['accident_link3'];}?>"/>

                         <?php if($row['accident_link3']!=0) { ?>

      					<div class="buttonwrapper"><a class="squarebutton" href="edit_sc.php?sc=<?php echo $row['accident_link3']?>"><span>Linked Accident 3</span></a></div>

        				<?php } ?>

                    </td>

                    <td <?php if($_SESSION['user_level'] == RR_LEVEL){echo 'class="middle-full-child"';} else{echo 'class="right-child"';}?> valign="top" cellspacing="0">

                    	&nbsp;

                    	 <?php if(1){ ?>

       				  <input type="submit" name="tow" value="Tow" style="font-size:14px; font-weight:bold;"/>

        				<?php } ?>

                        &nbsp;

                        <?php

						//survey 43

						if($_SESSION['user_level'] >= RR_LEVEL && isAcc($row['job']) && !$sid) { ?>

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

                        <?php if(allowAttendeeStatusChange($row['status']) ){?>

       				  <input type="submit" name="submit" value="Submit" style="font-size:14px; font-weight:bold;"/>

           				<?php } ?>

                    </td>

                    <?php

						if($_SESSION['user_level'] == RR_LEVEL){

					?>

                    	<td class="right-child" width="14%" valign="top">
                        </td>



                    <?php

						}

					?>

                    </tr>

                </table>

            </td></tr>

        <?php } ?>

        <tr><td colspan="5">&nbsp;</td></tr>

        <tr><td class="top-child_h" colspan="5" style="color:#148540">History</td></tr>

        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>

        <tr><td class="bottom-child" colspan="5">

        	<table width="100%" cellspacing="0">

        	 <?php

					$sql2 = "SELECT * FROM service_req WHERE id != '$id' AND `a_number`='$lic' AND `delete` = 0 order by CASE job

						WHEN 35 THEN 1

						ELSE 2

					END

					, STR_TO_DATE( `opendt` , '%m-%d-%Y' ) DESC, id DESC";

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

				mssql_close($connt);

				?>

        	</table>

        </td></tr>

        <tr><td colspan="5">&nbsp;</td></tr>

        <tr><td class="top-child_h" colspan="4" style="color:#148540"><h4>Rental</h4></td></tr>

        <tr><td class="middle-child" colspan="4">&nbsp;</td></tr>

        <tr><td colspan="4">

        	<table width="100%" cellspacing="0">



        	 <?php

					if(trim($temp_pol)!==''){

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

			$i=1;

			$dirname = FOLDER."rrimage/".$id;

			$thumbs = FOLDER."rrthumbs/".$id;

			$docs = FOLDER."rrdocs/".$id;

			$docst= FOLDER."rrdocsthumbs/".$id;

			$movs= FOLDER."rrmov/".$id;
			$audiod= FOLDER."rraudio/".$id;

			$documents = scandir($docs);

			$images = scandir($dirname);

			$movies=scandir($movs);
			$audios=scandir($audiod);
			$ignore = Array(".", "..");

			$n = 1;

			$r = 1;



			$sql22 = "SELECT * FROM service_req WHERE id = '$id'";

			$rs22 = mysql_query($sql22);

			$row22 = mysql_fetch_array($rs22);



			foreach($documents as $doc){

				$n_form='';

				$n_overview='';

				$n_damage_1='';

				$n_damage_2='';

				$n_policy_1='';

				$n_license_1='';

				$n_policy_2='';

				$n_license_2='';

				$n_policy_3='';

				$n_license_3='';

				$n_vin_1='';

				$n_vin_2='';

				$n_vin_3='';

				$n_vin_4='';

				$n_damage_3='';

				$n_damage_4='';

				$n_license_4='';

				$n_policy_4='';

				$inside_1='';

				$inside_2='';

				$inside_3='';

				if($row22['n_form']===$docs.'/'.urlencode($doc))  {$n_form='selected="selected"';}

				if($row22['n_overview']===$docs.'/'.urlencode($doc))  {$n_overview='selected="selected"';}

				if($row22['n_vin_1']===$docs.'/'.urlencode($doc))  {$n_vin='selected="selected"';}

				if($row22['n_vin_2']===$docs.'/'.urlencode($doc))  {$n_vin='selected="selected"';}

				if($row22['n_vin_3']===$docs.'/'.urlencode($doc))  {$n_vin='selected="selected"';}

				if($row22['n_vin_4']===$docs.'/'.urlencode($doc))  {$n_vin='selected="selected"';}

				if($row22['n_damage_1']===$docs.'/'.urlencode($doc))  {$n_damage_1='selected="selected"';}

				if($row22['n_damage_2']===$docs.'/'.urlencode($doc))  {$n_damage_2='selected="selected"';}

				if($row22['n_damage_3']===$docs.'/'.urlencode($doc))  {$n_damage_3='selected="selected"';}

				if($row22['n_damage_4']===$docs.'/'.urlencode($doc))  {$n_damage_4='selected="selected"';}

				if($row22['n_policy_1']===$docs.'/'.urlencode($doc))  {$n_policy_1='selected="selected"';}

				if($row22['n_license_1']===$docs.'/'.urlencode($doc))  {$n_license_1='selected="selected"';}

				if($row22['n_policy_2']===$docs.'/'.urlencode($doc))  {$n_policy_2='selected="selected"';}

				if($row22['n_license_2']===$docs.'/'.urlencode($doc))  {$n_license_2='selected="selected"';}

				if($row22['n_policy_3']===$docs.'/'.urlencode($doc))  {$n_policy_3='selected="selected"';}

				if($row22['n_license_3']===$docs.'/'.urlencode($doc))  {$n_license_3='selected="selected"';}

				if($row22['n_policy_4']===$docs.'/'.urlencode($doc))  {$n_policy_4='selected="selected"';}

				if($row22['n_license_4']===$docs.'/'.urlencode($doc))  {$n_license_4='selected="selected"';}

				if($row22['inside_1']===$docs.'/'.urlencode($doc))  {$inside_1='selected="selected"';}

				if($row22['inside_2']===$docs.'/'.urlencode($doc))  {$inside_2='selected="selected"';}

				if($row22['inside_3']===$docs.'/'.urlencode($doc))  {$inside_3='selected="selected"';}



				if(!in_array($doc,$ignore)){

					if($n == 1){

						echo ' <tr><td colspan="4" style="color:#148540">Document(s)</td></tr>';

						$n = 0;

					}

				if(file_exists($docst= FOLDER."rrdocsthumbs/".$id)){

					if($r==1){

					echo '<tr><td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.urlencode(substr($doc,0,-4).'.jpeg').'" /></a>';
					//echo '<tr><td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file=../'.$docst.'/'.urlencode(substr($doc,0,-4).'.jpeg').'" /></a>';
					}

					else if($r==4){

						echo '<td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.urlencode(substr($doc,0,-4).'.jpeg').'" /></a>';
						$r=0;

					}

					else{

						echo '<td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.urlencode(substr($doc,0,-4).'.jpeg').'" /></a>';
					}

					if($_SESSION['user_level'] == ADMIN_LEVEL && $r!=4){

					echo '</br>&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a>&nbsp;<select name="e-'.$i.'">

						<option value=""></value>

						<option value="form-'.$docs.'/'.urlencode($doc).'" '.$n_form.'>Form</form>

						<option value="overview-'.$docs.'/'.urlencode($doc).'" '.$n_overview.'>Overview</form>

						<option value="damage1-'.$docs.'/'.urlencode($doc).'" '.$n_damage_1.'>Damage A</form>

						<option value="damage2-'.$docs.'/'.urlencode($doc).'" '.$n_damage_2.'>Damage B</form>

						<option value="damage3-'.$docs.'/'.urlencode($doc).'" '.$n_damage_3.'>Damage C</form>

						<option value="damage4-'.$docs.'/'.urlencode($doc).'" '.$n_damage_4.'>Damage D</form>

						<option value="policy1-'.$docs.'/'.urlencode($doc).'" '.$n_policy_1.'>Policy A</form>

						<option value="license1-'.$docs.'/'.urlencode($doc).'" '.$n_license_1.'>License A</form>

						<option value="vin1-'.$docs.'/'.urlencode($doc).'" '.$n_vin_1.'>Vin A</form>

						<option value="policy2-'.$docs.'/'.urlencode($doc).'" '.$n_policy_2.'>Policy B</form>

						<option value="license2-'.$docs.'/'.urlencode($doc).'" '.$n_license_2.'>License B</form>

						<option value="vin2-'.$docs.'/'.urlencode($doc).'" '.$n_vin_2.'>Vin B</form>

						<option value="policy3-'.$docs.'/'.urlencode($doc).'" '.$n_policy_3.'>Policy C</form>

						<option value="license3-'.$docs.'/'.urlencode($doc).'" '.$n_license_3.'>License C</form>

						<option value="vin3-'.$docs.'/'.urlencode($doc).'" '.$n_vin_3.'>Vin C</form>

						<option value="policy4-'.$docs.'/'.urlencode($doc).'" '.$n_policy_4.'>Policy D</form>

						<option value="license4-'.$docs.'/'.urlencode($doc).'" '.$n_license_4.'>License D</form>

						<option value="vin4-'.$docs.'/'.urlencode($doc).'" '.$n_vin_4.'>Vin D</form>

						<option value="inside1-'.$docs.'/'.urlencode($doc).'" '.$inside_1.'>Inside A</form>

						<option value="inside2-'.$docs.'/'.urlencode($doc).'" '.$inside_2.'>Inside B</form>

						<option value="inside3-'.$docs.'/'.urlencode($doc).'" '.$inside_3.'>Inside C</form>

						</select></td>';

						$i++;

					}

					else if($_SESSION['user_level'] != ADMIN_LEVEL && $r!=4){

					echo '</br>&nbsp;<select name="e-'.$i.'">

						<option value=""></value>

						<option value="form-'.$docs.'/'.urlencode($doc).'" '.$n_form.'>Form</form>

						<option value="overview-'.$docs.'/'.urlencode($doc).'" '.$n_overview.'>Overview</form>

						<option value="damage1-'.$docs.'/'.urlencode($doc).'" '.$n_damage_1.'>Damage A</form>

						<option value="damage2-'.$docs.'/'.urlencode($doc).'" '.$n_damage_2.'>Damage B</form>

						<option value="damage3-'.$docs.'/'.urlencode($doc).'" '.$n_damage_3.'>Damage C</form>

						<option value="damage4-'.$docs.'/'.urlencode($doc).'" '.$n_damage_4.'>Damage D</form>

						<option value="policy1-'.$docs.'/'.urlencode($doc).'" '.$n_policy_1.'>Policy A</form>

						<option value="license1-'.$docs.'/'.urlencode($doc).'" '.$n_license_1.'>License A</form>

						<option value="vin1-'.$docs.'/'.urlencode($doc).'" '.$n_vin_1.'>Vin A</form>

						<option value="policy2-'.$docs.'/'.urlencode($doc).'" '.$n_policy_2.'>Policy B</form>

						<option value="license2-'.$docs.'/'.urlencode($doc).'" '.$n_license_2.'>License B</form>

						<option value="vin2-'.$docs.'/'.urlencode($doc).'" '.$n_vin_2.'>Vin B</form>

						<option value="policy3-'.$docs.'/'.urlencode($doc).'" '.$n_policy_3.'>Policy C</form>

						<option value="license3-'.$docs.'/'.urlencode($doc).'" '.$n_license_3.'>License C</form>

						<option value="vin3-'.$docs.'/'.urlencode($doc).'" '.$n_vin_3.'>Vin C</form>

						<option value="policy4-'.$docs.'/'.urlencode($doc).'" '.$n_policy_4.'>Policy D</form>

						<option value="license4-'.$docs.'/'.urlencode($doc).'" '.$n_license_4.'>License D</form>

						<option value="vin4-'.$docs.'/'.urlencode($doc).'" '.$n_vin_4.'>Vin D</form>

						<option value="inside1-'.$docs.'/'.urlencode($doc).'" '.$inside_1.'>Inside A</form>

						<option value="inside2-'.$docs.'/'.urlencode($doc).'" '.$inside_2.'>Inside B</form>

						<option value="inside3-'.$docs.'/'.urlencode($doc).'" '.$inside_3.'>Inside C</form>

						</select></td>';

						$i++;

					}




					else{

						echo '</br>&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a>&nbsp;<select name="e-'.$i.'">

						<option value=""></value>

						<option value="form-'.$docs.'/'.urlencode($doc).'" '.$n_form.'>Form</form>

						<option value="overview-'.$docs.'/'.urlencode($doc).'" '.$n_overview.'>Overview</form>

						<option value="damage1-'.$docs.'/'.urlencode($doc).'" '.$n_damage_1.'>Damage A</form>

						<option value="damage2-'.$docs.'/'.urlencode($doc).'" '.$n_damage_2.'>Damage B</form>

						<option value="damage3-'.$docs.'/'.urlencode($doc).'" '.$n_damage_3.'>Damage C</form>

						<option value="damage4-'.$docs.'/'.urlencode($doc).'" '.$n_damage_4.'>Damage D</form>

						<option value="policy1-'.$docs.'/'.urlencode($doc).'" '.$n_policy_1.'>Policy A</form>

						<option value="license1-'.$docs.'/'.urlencode($doc).'" '.$n_license_1.'>License A</form>

						<option value="vin1-'.$docs.'/'.urlencode($doc).'" '.$n_vin_1.'>Vin A</form>

						<option value="policy2-'.$docs.'/'.urlencode($doc).'" '.$n_policy_2.'>Policy B</form>

						<option value="license2-'.$docs.'/'.urlencode($doc).'" '.$n_license_2.'>License B</form>

						<option value="vin2-'.$docs.'/'.urlencode($doc).'" '.$n_vin_2.'>Vin B</form>

						<option value="policy3-'.$docs.'/'.urlencode($doc).'" '.$n_policy_3.'>Policy C</form>

						<option value="license3-'.$docs.'/'.urlencode($doc).'" '.$n_license_3.'>License C</form>

						<option value="vin3-'.$docs.'/'.urlencode($doc).'" '.$n_vin_3.'>Vin C</form>

						<option value="policy4-'.$docs.'/'.urlencode($doc).'" '.$n_policy_4.'>Policy D</form>

						<option value="license4-'.$docs.'/'.urlencode($doc).'" '.$n_license_4.'>License D</form>

						<option value="vin4-'.$docs.'/'.urlencode($doc).'" '.$n_vin_4.'>Vin D</form>

						<option value="inside1-'.$docs.'/'.urlencode($doc).'" '.$inside_1.'>Inside A</form>

						<option value="inside2-'.$docs.'/'.urlencode($doc).'" '.$inside_2.'>Inside B</form>

						<option value="inside3-'.$docs.'/'.urlencode($doc).'" '.$inside_3.'>Inside C</form>

						</select></td></tr>';

						$i++;

					}



					$r++;

				}

				else{

					echo '<tr><td colspan="4"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'">'.$doc.'</a></td></tr>';

				}





				}



			}

			if(is_dir(FOLDER.'rrdocs/'.$id)){

				echo '<tr><td colspan="4">&nbsp;</td></tr>

				<tr><td colspan="4"><a style="color:#DCB272" href="zip_download.php?dir=rrdocs/'.$id.'">Download All Document(s)</a></td></tr>';

			}

		?>

        <tr><td colspan="4">&nbsp;</td></tr>

        <?php

			$n = 1;

			$r=1;

			foreach($images as $curimg){

				if(!in_array($curimg, $ignore)) {

					$n_form='';

					$n_overview='';

					$n_damage_1='';

					$n_damage_2='';

					$n_policy_1='';

					$n_license_1='';

					$n_policy_2='';

					$n_license_2='';

					$n_policy_3='';

					$n_license_3='';

					$n_vin_1='';

					$n_vin_2='';

					$n_vin_3='';

					$n_vin_4='';

					$n_damage_3='';

					$n_damage_4='';

					$n_license_4='';

					$n_policy_4='';

					$inside_1='';

					$inside_2='';

					$inside_3='';

					if($row22['n_form']===$dirname.'/'.$curimg)  {$n_form='selected="selected"';}

					if($row22['n_overview']===$dirname.'/'.$curimg)  {$n_overview='selected="selected"';}

					if($row22['n_damage_1']===$dirname.'/'.$curimg)  {$n_damage_1='selected="selected"';}

					if($row22['n_damage_2']===$dirname.'/'.$curimg)  {$n_damage_2='selected="selected"';}

					if($row22['n_policy_1']===$dirname.'/'.$curimg)  {$n_policy_1='selected="selected"';}

					if($row22['n_license_1']===$dirname.'/'.$curimg)  {$n_license_1='selected="selected"';}

					if($row22['n_policy_2']===$dirname.'/'.$curimg)  {$n_policy_2='selected="selected"';}

					if($row22['n_license_2']===$dirname.'/'.$curimg)  {$n_license_2='selected="selected"';}

					if($row22['n_policy_3']===$dirname.'/'.$curimg)  {$n_policy_3='selected="selected"';}

					if($row22['n_license_3']===$dirname.'/'.$curimg)  {$n_license_3='selected="selected"';}

					if($row22['n_damage_3']===$dirname.'/'.$curimg)  {$n_damage_3='selected="selected"';}

					if($row22['n_vin_1']===$dirname.'/'.$curimg)  {$n_vin_1='selected="selected"';}

					if($row22['n_vin_2']===$dirname.'/'.$curimg)  {$n_vin_2='selected="selected"';}

					if($row22['n_vin_3']===$dirname.'/'.$curimg)  {$n_vin_3='selected="selected"';}

					if($row22['n_policy_4']===$dirname.'/'.$curimg)  {$n_policy_4='selected="selected"';}

					if($row22['n_license_4']===$dirname.'/'.$curimg)  {$n_license_4='selected="selected"';}

					if($row22['n_damage_4']===$dirname.'/'.$curimg)  {$n_damage_4='selected="selected"';}

					if($row22['n_vin_4']===$dirname.'/'.$curimg)  {$n_vin_4='selected="selected"';}

					if($row22['inside_1']===$dirname.'/'.$curimg)  {$inside_1='selected="selected"';}

					if($row22['inside_2']===$dirname.'/'.$curimg)  {$inside_2='selected="selected"';}

					if($row22['inside_3']===$dirname.'/'.$curimg)  {$inside_3='selected="selected"';}



					if($n == 1){

						echo ' <tr><td colspan="4" style="color:#148540">Image(s)</td></tr>';

						$n = 0;

					}

					if($r==1){

						echo'<tr>';

					}

					$i++;

					if($_SESSION['user_level'] >= POWER_LEVEL && $r==1){

					echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a>&nbsp;<select name="e-'.$i.'">

						</br>
						<option value=""></value>

						<option value="form-'.$dirname.'/'.$curimg.'" '.$n_form.'>Form</form>

						<option value="overview-'.$dirname.'/'.$curimg.'" '.$n_overview.'>Overview</form>

						<option value="damage1-'.$dirname.'/'.$curimg.'" '.$n_damage_1.'>Damage A</form>

						<option value="damage2-'.$dirname.'/'.$curimg.'" '.$n_damage_2.'>Damage B</form>

						<option value="damage3-'.$dirname.'/'.$curimg.'" '.$n_damage_3.'>Damage C</form>

						<option value="damage4-'.$dirname.'/'.$curimg.'" '.$n_damage_4.'>Damage D</form>

						<option value="policy1-'.$dirname.'/'.$curimg.'" '.$n_policy_1.'>Policy A</form>

						<option value="license1-'.$dirname.'/'.$curimg.'" '.$n_license_1.'>License A</form>

						<option value="vin1-'.$dirname.'/'.$curimg.'" '.$n_vin_1.'>VIN A</form>

						<option value="policy2-'.$dirname.'/'.$curimg.'" '.$n_policy_2.'>Policy B</form>

						<option value="license2-'.$dirname.'/'.$curimg.'" '.$n_license_2.'>License B</form>

						<option value="vin2-'.$dirname.'/'.$curimg.'" '.$n_vin_2.'>VIN B</form>

						<option value="policy3-'.$dirname.'/'.$curimg.'" '.$n_policy_3.'>Policy C</form>

						<option value="license3-'.$dirname.'/'.$curimg.'" '.$n_license_3.'>License C</form>

						<option value="vin3-'.$dirname.'/'.$curimg.'" '.$n_vin_3.'>VIN C</form>

						<option value="policy4-'.$dirname.'/'.$curimg.'" '.$n_policy_4.'>Policy D</form>

						<option value="license4-'.$dirname.'/'.$curimg.'" '.$n_license_4.'>License D</form>

						<option value="vin4-'.$dirname.'/'.$curimg.'" '.$n_vin_4.'>VIN D</form>

						<option value="inside1-'.$dirname.'/'.$curimg.'" '.$inside_1.'>Inside A</form>

						<option value="inside2-'.$dirname.'/'.$curimg.'" '.$inside_2.'>Inside B</form>

						<option value="inside3-'.$dirname.'/'.$curimg.'" '.$inside_3.'>Inside C</form>

						</select></td>';

					}

					else if($_SESSION['user_level'] >= POWER_LEVEL){

						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a>&nbsp;<select name="e-'.$i.'">
						<option value=""></value>

						<option value="form-'.$dirname.'/'.$curimg.'" '.$n_form.'>Form</form>

						<option value="overview-'.$dirname.'/'.$curimg.'" '.$n_overview.'>Overview</form>

						<option value="damage1-'.$dirname.'/'.$curimg.'" '.$n_damage_1.'>Damage A</form>

						<option value="damage2-'.$dirname.'/'.$curimg.'" '.$n_damage_2.'>Damage B</form>

						<option value="damage3-'.$dirname.'/'.$curimg.'" '.$n_damage_3.'>Damage C</form>

						<option value="damage4-'.$dirname.'/'.$curimg.'" '.$n_damage_4.'>Damage D</form>

						<option value="policy1-'.$dirname.'/'.$curimg.'" '.$n_policy_1.'>Policy A</form>

						<option value="license1-'.$dirname.'/'.$curimg.'" '.$n_license_1.'>License A</form>

						<option value="vin1-'.$dirname.'/'.$curimg.'" '.$n_vin_1.'>VIN A</form>

						<option value="policy2-'.$dirname.'/'.$curimg.'" '.$n_policy_2.'>Policy B</form>

						<option value="license2-'.$dirname.'/'.$curimg.'" '.$n_license_2.'>License B</form>

						<option value="vin2-'.$dirname.'/'.$curimg.'" '.$n_vin_2.'>VIN B</form>

						<option value="policy3-'.$dirname.'/'.$curimg.'" '.$n_policy_3.'>Policy C</form>

						<option value="license3-'.$dirname.'/'.$curimg.'" '.$n_license_3.'>License C</form>

						<option value="vin3-'.$dirname.'/'.$curimg.'" '.$n_vin_3.'>VIN C</form>

						<option value="policy4-'.$dirname.'/'.$curimg.'" '.$n_policy_4.'>Policy D</form>

						<option value="license4-'.$dirname.'/'.$curimg.'" '.$n_license_4.'>License D</form>

						<option value="vin4-'.$dirname.'/'.$curimg.'" '.$n_vin_4.'>VIN D</form>

						<option value="inside1-'.$dirname.'/'.$curimg.'" '.$inside_1.'>Inside A</form>

						<option value="inside2-'.$dirname.'/'.$curimg.'" '.$inside_2.'>Inside B</form>

						<option value="inside3-'.$dirname.'/'.$curimg.'" '.$inside_3.'>Inside C</form>

						</select></td>';

					}

					else if($_SESSION['user_level'] >= RR_LEVEL && $r==1){

						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a>&nbsp;<select name="e-'.$i.'">
						<option value=""></value>

						<option value="form-'.$dirname.'/'.$curimg.'" '.$n_form.'>Form</form>

						<option value="overview-'.$dirname.'/'.$curimg.'" '.$n_overview.'>Overview</form>

						<option value="damage1-'.$dirname.'/'.$curimg.'" '.$n_damage_1.'>Damage A</form>

						<option value="damage2-'.$dirname.'/'.$curimg.'" '.$n_damage_2.'>Damage B</form>

						<option value="damage3-'.$dirname.'/'.$curimg.'" '.$n_damage_3.'>Damage C</form>

						<option value="damage4-'.$dirname.'/'.$curimg.'" '.$n_damage_4.'>Damage D</form>

						<option value="policy1-'.$dirname.'/'.$curimg.'" '.$n_policy_1.'>Policy A</form>

						<option value="license1-'.$dirname.'/'.$curimg.'" '.$n_license_1.'>License A</form>

						<option value="vin1-'.$dirname.'/'.$curimg.'" '.$n_vin_1.'>VIN A</form>

						<option value="policy2-'.$dirname.'/'.$curimg.'" '.$n_policy_2.'>Policy B</form>

						<option value="license2-'.$dirname.'/'.$curimg.'" '.$n_license_2.'>License B</form>

						<option value="vin2-'.$dirname.'/'.$curimg.'" '.$n_vin_2.'>VIN B</form>

						<option value="policy3-'.$dirname.'/'.$curimg.'" '.$n_policy_3.'>Policy C</form>

						<option value="license3-'.$dirname.'/'.$curimg.'" '.$n_license_3.'>License C</form>

						<option value="vin3-'.$dirname.'/'.$curimg.'" '.$n_vin_3.'>VIN C</form>

						<option value="policy4-'.$dirname.'/'.$curimg.'" '.$n_policy_4.'>Policy D</form>

						<option value="license4-'.$dirname.'/'.$curimg.'" '.$n_license_4.'>License D</form>

						<option value="vin4-'.$dirname.'/'.$curimg.'" '.$n_vin_4.'>VIN D</form>

						<option value="inside1-'.$dirname.'/'.$curimg.'" '.$inside_1.'>Inside A</form>

						<option value="inside2-'.$dirname.'/'.$curimg.'" '.$inside_2.'>Inside B</form>

						<option value="inside3-'.$dirname.'/'.$curimg.'" '.$inside_3.'>Inside C</form>

						</select></td>';

					}

					else if($_SESSION['user_level'] >= RR_LEVEL){

						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a>&nbsp;<select name="e-'.$i.'">
						<option value=""></value>

						<option value="form-'.$dirname.'/'.$curimg.'" '.$n_form.'>Form</form>

						<option value="overview-'.$dirname.'/'.$curimg.'" '.$n_overview.'>Overview</form>

						<option value="damage1-'.$dirname.'/'.$curimg.'" '.$n_damage_1.'>Damage A</form>

						<option value="damage2-'.$dirname.'/'.$curimg.'" '.$n_damage_2.'>Damage B</form>

						<option value="damage3-'.$dirname.'/'.$curimg.'" '.$n_damage_3.'>Damage C</form>

						<option value="damage4-'.$dirname.'/'.$curimg.'" '.$n_damage_4.'>Damage D</form>

						<option value="policy1-'.$dirname.'/'.$curimg.'" '.$n_policy_1.'>Policy A</form>

						<option value="license1-'.$dirname.'/'.$curimg.'" '.$n_license_1.'>License A</form>

						<option value="vin1-'.$dirname.'/'.$curimg.'" '.$n_vin_1.'>VIN A</form>

						<option value="policy2-'.$dirname.'/'.$curimg.'" '.$n_policy_2.'>Policy B</form>

						<option value="license2-'.$dirname.'/'.$curimg.'" '.$n_license_2.'>License B</form>

						<option value="vin2-'.$dirname.'/'.$curimg.'" '.$n_vin_2.'>VIN B</form>

						<option value="policy3-'.$dirname.'/'.$curimg.'" '.$n_policy_3.'>Policy C</form>

						<option value="license3-'.$dirname.'/'.$curimg.'" '.$n_license_3.'>License C</form>

						<option value="vin3-'.$dirname.'/'.$curimg.'" '.$n_vin_3.'>VIN C</form>

						<option value="policy4-'.$dirname.'/'.$curimg.'" '.$n_policy_4.'>Policy D</form>

						<option value="license4-'.$dirname.'/'.$curimg.'" '.$n_license_4.'>License D</form>

						<option value="vin4-'.$dirname.'/'.$curimg.'" '.$n_vin_4.'>VIN D</form>

						<option value="inside1-'.$dirname.'/'.$curimg.'" '.$inside_1.'>Inside A</form>

						<option value="inside2-'.$dirname.'/'.$curimg.'" '.$inside_2.'>Inside B</form>

						<option value="inside3-'.$dirname.'/'.$curimg.'" '.$inside_3.'>Inside C</form>

						</select></td>';

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
			$r=1;
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

						echo '<td width="25%">
						<video controls style="width: 275px;">
							<source src="download.php?file='.$movs.'/'.$curmov.'" type="video/mp4">
						</video>
						 <br/><a style="color:#DCB272" href="delete.php?file='.$movs.'/'.$curmov.'&sc='.$id.'">Delete</a></td>';

					}

					else if($_SESSION['user_level'] >= POWER_LEVEL){

						echo '<td width="25%">
						<video controls style="width: 275px;">
							<source src="download.php?file='.$movs.'/'.$curmov.'" type="video/mp4">
						</video>
						<br/><a style="color:#DCB272" href="delete.php?file='.$movs.'/'.$curmov.'&sc='.$id.'">Delete</a></td>';

					}

					else if($_SESSION['user_level'] >= RR_LEVEL && $r==1){

						echo '<td width="25%">	<video controls style="width: 275px;">
								<source src="download.php?file='.$movs.'/'.$curmov.'" type="video/mp4">
							</video></td>';

					}

					else if($_SESSION['user_level'] >= RR_LEVEL){

						echo '<td width="25%">	<video controls style="width: 275px;">
								<source src="download.php?file='.$movs.'/'.$curmov.'" type="video/mp4">
							</video></td>';

					}

					else if($r==1){

						echo '<td width="25%">	<video controls  style="width: 275px;">
								<source src="download.php?file='.$movs.'/'.$curmov.'" type="video/mp4">
							</video></td>';

					}

					else{

						echo '<td width="25%">	<video controls  style="width: 275px;">
								<source src="download.php?file='.$movs.'/'.$curmov.'" type="video/mp4">
							</video></td>';

					}


					if($r==4){
						echo '</tr>';
						$r = 0;
					}

					$r++;

				}

			} // end for each $movies


			if($r!=1){

				while($r!= 5){

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

			$n = 1;
			$r=1;
			foreach($audios as $audio){
				if(!in_array($audio, $ignore)) {
					if($n == 1){
						echo ' <tr><td colspan="4" style="color:#148540">Audio(s)</td></tr>';
						$n = 0;
					}
					if($r==1){
						echo'<tr>';
					}


					if($_SESSION['user_level'] >= POWER_LEVEL && $r==1){

						echo '<td width="25%">
						<audio controls style="width: 250px;">
							<source src="download.php?file='.$audiod.'/'.$audio.'" type="audio/mpeg">
						</audio>
						 <br/><a style="color:#DCB272" href="delete.php?file='.$audiod.'/'.$audio.'&sc='.$id.'">Delete</a></td>';

					}

					else if($_SESSION['user_level'] >= POWER_LEVEL){

						echo '<td width="25%">
						<audio controls style="width: 250px;>
							<source src="download.php?file='.$audiod.'/'.$audio.'" type="audio/mpeg">
						</audio>
						<br/><a style="color:#DCB272" href="delete.php?file='.$audiod.'/'.$audio.'&sc='.$id.'">Delete</a></td>';

					}

					else if($_SESSION['user_level'] >= RR_LEVEL && $r==1){

						echo '<td width="25%"><audio controls  style="width: 250px;">
						<audio controls width=150 heigh=200>
							<source src="download.php?file='.$audiod.'/'.$audio.'" type="audio/mpeg">
						</audio></td>';

					}

					else if($_SESSION['user_level'] >= RR_LEVEL){

						echo '<td width="25%"><audio controls  style="width: 250px;">
						<audio controls width=150 heigh=200>
							<source src="download.php?file='.$audiod.'/'.$audio.'" type="audio/mpeg">
						</audio></td>';

					}

					else if($r==1){

						echo '<td width="25%"><audio controls  style="width: 250px;">
							<source src="download.php?file='.$audiod.'/'.$audio.'" type="audio/mpeg">
						</audio></td>';

					}

					else{

						echo '<td width="25%"><audio controls  style="width: 250px;">
							<source src="download.php?file='.$audiod.'/'.$audio.'" type="audio/mpeg">
						</audio></td>';

					}


					if($r==4){
						echo '</tr>';
						$r = 0;
					}

					$r++;

				}

			} // end for each $audios


			if($r!=1){

				while($r!= 5){

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



			if (is_dir(FOLDER.'rrimage/'.$id) && $_SESSION['user_level'] >= RR_LEVEL) {

				echo '<a style="color:#DCB272" href="zip_download.php?dir=rrimage/'.$id.'">Download All Image(s)</a>';

			}

			?></td></tr>

            </table>



       	<?php if($master_sc && $jid != 3 && $jid != 29 && $jid != 12 && $jid != 33){ ?>

        <tr><td colspan="5">&nbsp;</td></tr>



        <tr><td class="child" colspan="5"><table width="100%" cellspacing="0">

        <?php



			$id = $master_sc;

			$dirname = FOLDER."rrimage/".$id;

			$thumbs = FOLDER."rrthumbs/".$id;

			$docs = FOLDER."rrdocs/".$id;

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

				if(file_exists($docst= FOLDER."rrdocsthumbs/".$id)){

					if($r==1){

						echo '<tr><td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.substr($doc,0,-4).'.jpeg'.'" /></a>';
						//echo '<tr><td width="25%"><a style="color:#DCB272" href="'.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.substr($doc,0,-4).'.jpeg'.'" /></a>';
					}

					else if($r==4){

						echo '<td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.substr($doc,0,-4).'.jpeg'.'" /></a>';
						$r=0;

					}

					else{

						echo '<td width="25%"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'"><img width="275" src="download.php?file='.$docst.'/'.substr($doc,0,-4).'.jpeg'.'" /></a>';

					}

					if($_SESSION['user_level'] > RR_LEVEL && $r!=4){

					echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a></td>';

					}
					else 	if($_SESSION['user_level'] > RR_LEVEL){

						//echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a></td>';
						echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a></td></tr>';
					}
					/*else{

						echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a></td></tr>';

					}*/



					$r++;

				}

				}

			}

			if(is_dir(FOLDER.'rrdocs/'.$id)){

				echo '<tr><td colspan="4">&nbsp;</td></tr>

				<tr><td colspan="4"><a style="color:#DCB272" href="zip_download.php?dir=rrdocs/'.$id.'">Download All Document(s)</a></td></tr>';

			}

		?>

        <tr><td colspan="4">&nbsp;</td></tr>

        <?php

			$n = 1;
			$r=1;
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



					if($_SESSION['user_level'] > RR_LEVEL && $r==1){

						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="275" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}

					else if($_SESSION['user_level'] > RR_LEVEL){

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



			if (is_dir(FOLDER.'rrimage/'.$id) && $_SESSION['user_level'] >= RR_LEVEL) {

				echo '<a style="color:#DCB272" href="zip_download.php?dir=rrimage/'.$id.'">Download All Image(s)</a>';

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

 <?php //if($row['job'] == 7 && !is_dir('rrimage/'.$id) && 0){ ?>

 	//frmvalidator.addValidation("image_upload_box","req","Accident, you are required to upload at least one image","VWZ_IsChecked(document.forms['edit_sc'].elements['present'],'Other')");

  <?php //} ?>

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
