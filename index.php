<?php

include 'dbc.php';

date_default_timezone_set('America/Aruba');

page_protect();

include "support/function.php";

//session_start();error_reporting(E_ALL);
//INI_SET('display_errors', '1');

$page = $_REQUEST['page'];if(!$page){

	$page = 1;

}

$paging = 30; //items per page

$page_dif = 5;

$col1 = 45;

$col2 = 132;

$col3 = 75;

$col4 = 50;

$flash=1;

$flashg=1;

if(isset($_POST[vehicle_model])){

	//do nothing

}

else{

	unset($_POST[vehicle_model]);

	unset($_POST[vehicle_make]);

	unset($_POST[vehicle_year]);

}

if($_REQUEST['status']){

	$fstatus = $_REQUEST['status'];

}

else{

	$fstatus = 0;

}

if($_REQUEST['job']){

	$fjob = $_REQUEST['job'];

}

else{

	$fjob = 0;

}

if(isPolice()){

	$fjob=32;

}

if($_REQUEST['aid']){

	$faid = $_REQUEST['aid'];

}

else{

	$faid = 0;

}

if($_REQUEST['persoonsFilter']){

	$persoonsFilter = $_REQUEST['persoonsFilter'];

}

else{

	$persoonsFilter = '';

}

if($_REQUEST['phone']){

	$phone = $_REQUEST['phone'];

}

else{

	$phone = '';

}

if($_REQUEST['policyFilter']){

	$policyFilter = $_REQUEST['policyFilter'];

}

else{

	$policyFilter = '';

}

if($_REQUEST['admFilter']){

	$admFilter = $_REQUEST['admFilter'];

}

else{

	$admFilter = '';

}

if($_REQUEST['vin']){

	$vin = $_REQUEST['vin'];

}

else{

	$vin = '';

}

if($_REQUEST['sid']){

	$sid = $_REQUEST['sid'];

}

else{

	$sid = '';

}

$rhd=$_REQUEST['rhd'];

if($_REQUEST['claimsFilter']){

	$claimsFilter = $_REQUEST['claimsFilter'];

}

else{

	$claimsFilter = '';

}

if($_REQUEST['bill_to']!=100 && isset($_REQUEST['bill_to']) ){

	$bill_to = $_REQUEST['bill_to'];

}

else{

	$bill_to = 100;

}

if($_REQUEST['receipt']){

	$receipt = $_REQUEST['receipt'];

}

else{

	$receipt = '';

}

if($_REQUEST['clientno']){

	$clientno = $_REQUEST['clientno'];

}

else{

	$clientno = '';

}

if($_REQUEST['licenseNo']){

	$licenseNo = $_REQUEST['licenseNo'];

}

else{

	$licenseNo = '';

}

if($_REQUEST['mr']){

	$mr = $_REQUEST['mr'];

}

else{

	$mr = 0;

}

if($_REQUEST['insured_not']){

	$insured_not = $_REQUEST['insured_not'];

}

else{

	$insured_not = 0;

}

if($_REQUEST['district']){

	$district = $_REQUEST['district'];

}

else{

	$district = 0;

}

if($_REQUEST['claimsAtt']){

	$claimsAtt=$_REQUEST['claimsAtt'];

}

else{

	$claimsAtt=0;

}

if($_REQUEST['adj_fee']){

	$adj_fee = $_REQUEST['adj_fee'];

}

else{

	$adj_fee = 0;

}

if($_REQUEST['nrs_fee']){

	$nrs_fee = $_REQUEST['nrs_fee'];

}

else{

	$nrs_fee = 0;

}

if($_REQUEST['rid']!==''){

	$rid=$_REQUEST['rid'];

}

else{

	$rid='';

}

if($rid!==''){

	$sql4="SELECT * FROM `rental` WHERE `id`='$rid'";

	$rs4=mysql_query($sql4);

	if($row4=mysql_fetch_array($rs4)){

		$sid=$row4['service_req_id'];

	}

}

$sdate = $_REQUEST['sdate'];

$edate = $_REQUEST['edate'];

$anumber = $_REQUEST['anumber'];

if(strcmp($_REQUEST['clear'],'Clear') == 0){ //Clear filter

	$fstatus = 0;

	$faid = 0;

	$fjob = 0;

	$sdate = '';

	$edate = '';

	$anumber = '';

	$sid = '';

	$mr = 0;

	$receipt = '';

	$clientno = '';

	$vin = '';

	$bill_to = 100;

	$licenseNo = '';

	$insured_not = 0;

	$district = 0;

	$claimsFilter = '';

	$persoonsFilter = '';

	$claimsAtt='';

	$policyFilter='';

	$admFilter='';

	$nrs_fee=0;

	$adj_fee=0;

	$phone='';

	$rid='';

	$rhd=0;

	unset($_POST[vehicle_year]);

	unset($_POST[vehicle_make]);

	unset($_POST[vehicle_model]);

}

echo menu();

if($sid != 0 || strlen($receipt) != 0 || $rid !=0){

	if(strlen($receipt) != 0){

		$sql = "SELECT * FROM `service_req` WHERE `receipt`='$receipt'";

		$rs = mysql_query($sql);

		if(mysql_num_rows($rs) != 0 || $sid != 0){

			if(mysql_num_rows($rs) != 0){

				$row = mysql_fetch_array($rs);

				$sid = $row['id'];

			}

			?>

			<script type="text/javascript">

                window.open("edit_sc.php?sc=<?php echo $sid?>","_self");

            </script>

            <?php

            $fstatus = 0;

            $faid = 0;

            $fjob = 0;

            $sdate = '';

            $edate = '';

            $anumber = '';

            $sid = '';

            $mr = 0;

            $receipt = '';

			$clientno = '';

			$vin = '';

			$bill_to = 100;

			$licenseNo = '';

			$insured_not = 0;

			$district = 0;

			$claimsAtt='';

			$nrs_fee=0;

			$adj_fee=0;

			$phone='';

			$rid='';

			$rhd=0;

		}

	}

	else{

		?>

			<script type="text/javascript">

                window.open("edit_sc.php?sc=<?php echo $sid?>","_self");

            </script>

            <?php

            $fstatus = 0;

            $faid = 0;

            $fjob = 0;

            $sdate = '';

            $edate = '';

            $anumber = '';

            $sid = '';

            $mr = 0;

            $receipt = '';

			$clientno = '';

			$vin = '';

			$bill_to = 100;

			$licenseNo = '';

			$insured_not = 0;

			$district = 0;

			$claimsAtt='';

			$nrs_fee=0;

			$adj_fee=0;

			$phone='';

			$rid='';

			$rhd=0;

	}

}

$sql3 = "SELECT COUNT(PolicyNo) as t FROM VW_VEHICLE WHERE VehStatus='A'";

$rs3= mssql_query($sql3);

$row3 = mssql_fetch_array($rs3);

$tam = $row3['t'];

?>

<meta http-equiv="refresh" content="90" >

<table width="1200" align="center" cellspacing="0">

    	<tr>

        	<td colspan="12" align="center" ><h8 style="color:#FFF">Service List</h8></td>

        </tr>

         <?php if($_SESSION['user_level']>2){ ?><tr>

         	<td colspan="6" align="left" style="color:#FFF"><h9 style="color:#FFF"><?php echo date('F j, Y g:i A');?></h9></td>

         	<td colspan="6" align="right" style="color:#FFF"><h9 style="color:#FFF">Total Insured Vehicles: <?php echo $tam;?></h9>

            </td></tr><?php }?>

        	<tr>

      	<td colspan="12" class="child" >

       	<?php

			$sql4 = "SELECT * FROM users WHERE id='".$_SESSION['user_id']."'";

			$rs4 = mysql_query($sql4);

			$row4 = mysql_fetch_array($rs4);

			if($row4['clientId']==0){

		?>

        <?php

			if($_SESSION['user_id']==54){

				echo $_SESSION['country'].' '.DB_NAME;

			}

		?>

        <form name="filter" action="" method="post">

        	<table width="100%">

            	<tr>

                	<td colspan="6">

                        Attendee: <select name="aid" style="background-color:#FAD090" onkeypress="submit_form(event);">

                        <option <?php if($faid==0){echo 'selected="selected"';} if(checkEXT()){echo 'disabled="disabled"';}?> value="0">All</option>

                        <?php

                            if(checkEXT()){



                                $user_id = $_SESSION['user_id'];

                                $sql2 = "SELECT attendee_id FROM users WHERE id='$user_id'";

                                $rs2 = mysql_query($sql2);

                                $row2 = mysql_fetch_array($rs2);

                                $faid = $aid = $row2['attendee_id'];

                                $sql = "SELECT * FROM attendee WHERE id != 10 AND active=1 order by s_name ";

                                $rs = mysql_query($sql);

                                while($row=mysql_fetch_array($rs)){

                                    if($aid == $row['id']){

                                        echo '<option selected="selected" value="'.$row['id'].'">'.$row['s_name'].'</option>';

                                    }

                                    else{

                                        echo '<option disabled="disabled" value="'.$row['id'].'">'.$row['s_name'].'</option>';

                                    }

                                }

                            }

                            else{

                                $sql = "SELECT * FROM attendee WHERE id != 10 AND active=1 order by s_name ";

                                $rs = mysql_query($sql);

                                while($row=mysql_fetch_array($rs)){

                                    if($faid == $row['id']){

                                        echo '<option selected="selected" value="'.$row['id'].'">'.$row['s_name'].'</option>';

                                    }

                                    else{

                                        echo '<option value="'.$row['id'].'">'.$row['s_name'].'</option>';

                                    }

                                }

                            }

                        ?>

                    </select> Status:

                    <select name="status" style="background-color:#FAD090" onkeypress="submit_form(event);">

                       <option <?php if($fstatus==0){echo 'selected="selected"';}?> value="0">All</option>

                        <?php

                            $sql = "SELECT * FROM status order by `status`";

                            $rs = mysql_query($sql);

                            while($row=mysql_fetch_array($rs)){

                                if($fstatus == $row['id']){

                                    echo '<option selected="selected" value="'.$row['id'].'">'.$row['status'].'</option>';

                                }

                                else{

                                    echo '<option value="'.$row['id'].'">'.$row['status'].'</option>';

                                }

                            }

                        ?>

                    </select> Jobs: <select name="job" style="background-color:#FAD090" onkeypress="submit_form(event);">

                       <option <?php if($fjob==0){echo 'selected="selected"';}?> value="0">All</option>

                        <?php

                            $sql = "SELECT * FROM jobs order by `description` ";

                            $rs = mysql_query($sql);

                            while($row=mysql_fetch_array($rs)){

                                if($fjob == $row['id']){

                                    echo '<option selected="selected" value="'.$row['id'].'">'.$row['description'].'</option>';

                                }

                                else{

                                    echo '<option value="'.$row['id'].'">'.$row['description'].'</option>';

                                }

                            }

                        ?>

                    </select> Date From: <input type="text" id="sdate" name="sdate" readonly/ size="13" value="<?php echo $sdate;?>"/>

              <button id="sdatebutton">

                <img src="anytime/calendar.png" alt="[calendar icon]"/>

              </button>

              <script>

                $('#sdatebutton').click(

                  function(e) {

                    $('#sdate').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();

                    e.preventDefault();

                  } );

              </script> To <input type="text" id="edate" name="edate" readonly size="13" value="<?php echo $edate;?>"/>

              <button id="edatebutton">

                <img src="anytime/calendar.png" alt="[calendar icon]"/>

              </button>

              <script>

                $('#edatebutton').click(

                  function(e) {

                    $('#edate').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();

                    e.preventDefault();

                  } );

              </script>

  		</td>

	</tr>

    <tr>

    	<td width="125">A-Number:</td>

        <td width="300"><input type="text" name="anumber" style="background-color:#FAD090" value="<?php echo $anumber;?>" onkeypress="submit_form(event);"/></td>

        <td width="125">ID:</td>

        <td width="300"><input type ="text" name="sid" style="background-color:#FAD090" value="<?php echo $sid;?>" size="7" onkeypress="submit_form(event);"/>&nbsp;RHD:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="rhd" style="background-color:#FAD090">

            	<option <?php if($rhd ==0){ echo 'selected="selected"';} ?> value="0">All</option>

                <option <?php if($rhd ==1){ echo 'selected="selected"';} ?> value="1">Yes</option>

                <option <?php if($rhd ==2){ echo 'selected="selected"';} ?> value="2">No</option>

            </select>

        </td>

        <td width="125">Drivers License:</td>

        <td width="325"><input type="text" name="licenseNo" style="background-color:#FAD090" value="<?php echo $licenseNo;?>" onkeypress="submit_form(event);"/></td>

   	</tr>

     <tr>

    	<td width="125">Vin No:</td>

        <td><input type="text" name="vin" size="30" style="background-color:#FAD090" value="<?php echo $vin;?>" onkeypress="submit_form(event);"/></td>

         <td width="125">Receipt:</td>

        <td width="325"><input type ="text" name="receipt" style="background-color:#FAD090" value="<?php echo $receipt;?>" size="8" onkeypress="submit_form(event);"/></td>

        <td width="125">Pers. Number:</td>

        <td width="325"><input type="text" name="persoonsFilter" style="background-color:#FAD090" value="<?php echo $persoonsFilter;?>" onkeypress="submit_form(event);"/></td>

    </tr>

    <tr>

    	<td width="125">Vehicle Year</td>

        <td>

        <select name="vehicle_year" id="vehicle_year" style="background-color:#FAD090">

        	<option value=""></option>

        	<?php

				$sqlt='SELECT DISTINCT(`begin`) `y` FROM `vehicles` WHERE 1 ORDER BY `begin` DESC';

				$rst=mysql_query($sqlt);

				while($rowt=mysql_fetch_array($rst)){

					if($_POST[vehicle_year]==$rowt['y']){

						echo '<option selected="selected" value="'.$rowt['y'].'">'.$rowt['y'].'</option>';

					}

					else{

						echo '<option value="'.$rowt['y'].'">'.$rowt['y'].'</option>';

					}

				}

			?>

        </select> Coverage <select name="coverage" style="background-color:#FAD090">

       		<option value=""></option>

            <option value="SC">SC</option>

            <option value="C">C</option>

            <option value="TPC">TPC</option>

            <option value="TP">TP</option>

        </select>

        </td>

        <td width="125">Rental ID:</td>

        <td width="325"><input type ="text" name="rid" style="background-color:#FAD090" value="<?php echo $rid;?>" size="7" onkeypress="submit_form(event);"/></td>

        <td width="125">Adm. Number:</td>

        <td width="325"><input type ="text" name="admFilter" style="background-color:#FAD090" value="<?php echo $admFilter;?>" onkeypress="submit_form(event);"/></td>

    </tr>

    <tr>

    	<td width="125">Vehicle Make</td>

        <td>

        <select name="vehicle_make" id="vehicle_make" style="background-color:#FAD090">

                        <?php

							if(!isset($_POST[vehicle_make]) || $_POST[vehicle_year]===''){

						?>

                        <option value="">..Select Year</option>

                        <?php

							}

							else{

								$sqlt = "SELECT make FROM vehicles GROUP BY Make";

								$rst = mysql_query($sqlt);

								while($rowt = mysql_fetch_array($rst)){

									if($_POST[vehicle_make] === $rowt[make]){

										echo '<option value="'.$rowt[make].'" selected="selected">'.$rowt[make].'</option>';

									}

									else{

										echo '<option value="'.$rowt[make].'">'.$rowt[make].'</option>';

									}

								}

							}

						?>

                        </select>

        </td>

        <td width="125">NRS Fee:</td>

        <td width="325"><select name="nrs_fee" style="background-color:#FAD090">

            	<option <?php if($nrs_fee ==0){ echo 'selected="selected"';} ?> value="0">All</option>

                <option <?php if($nrs_fee ==1){ echo 'selected="selected"';} ?> value="1">Yes</option>

                <option <?php if($nrs_fee ==2){ echo 'selected="selected"';} ?> value="2">No</option>

            </select> &nbsp;Adjuster Fee:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="adj_fee" style="background-color:#FAD090">

            	<option <?php if($adj_fee ==0){ echo 'selected="selected"';} ?> value="0">All</option>

                <option <?php if($adj_fee ==1){ echo 'selected="selected"';} ?> value="1">Yes</option>

                <option <?php if($adj_fee ==2){ echo 'selected="selected"';} ?> value="2">No</option>

            </select>

        </td>

        <td width="125">Policy Number:</td>

        <td width="325"><input type ="text" name="policyFilter" style="background-color:#FAD090" value="<?php echo $policyFilter;?>" onkeypress="submit_form(event);"/></td>

   	</tr>

    <tr>

    	<td width="125">Vehicle Model:</td>

        <td>

         <select name="vehicle_model" id="vehicle_model" style="background-color:#FAD090">

                         <?php

						if(!isset($_POST[vehicle_model])){

						 ?>

                        <option value="">..Select Make</option>

                        <?php

						}

						else{

						$sql10 = "SELECT * FROM vehicles WHERE make='".$_POST[vehicle_make]."' ORDER BY model";

						$rs10 = mysql_query($sql10);

						while($row10 = mysql_fetch_array($rs10)){



							$min = $row10['begin'];

							$max = $row10['end'];



							if($row10[model] === $_POST['vehicle_model']){

								echo '<option value="'.$row10[model].'" selected="selected">'.$row10[model].'</option>';

							}

							else if($row10[year]==$_POST[vehicle_year]){

								echo '<option value="'.$row10[Model].'">'.$row10[Model].'</option>';

							}

							else if($_POST[vehicle_year] >= $min && $_POST[vehicle_year]  <= $max){

								echo '<option value="'.$row10[model].'">'.$row10[model].'</option>';

							}

						}

						}

						?>

                        </select>

        </td>

       	<td width="125">Insured:</td>

        <td width="325">

        	<select name="insured_not" style="background-color:#FAD090">

            	<option <?php if($insured_not ==0){ echo 'selected="selected"';} ?> value="0">All</option>

                <option <?php if($insured_not ==1){ echo 'selected="selected"';} ?> value="1">Yes</option>

                <option <?php if($insured_not ==2){ echo 'selected="selected"';} ?> value="2">No</option>

            </select> &nbsp;Money Received:&nbsp;<select name="mr" style="background-color:#FAD090">

           <option <?php if($mr==0){echo 'selected="selected"';}?> value="0">All</option>

           <option <?php if($mr==-1){echo 'selected="selected"';}?> value="-1">No</option>

           <option <?php if($mr==1){echo 'selected="selected"';}?> value="1">Yes</option>

       	</select>

        </td>

         <td width="125">Claims Number:</td>

        <td width="325"><input type ="text" name="claimsFilter" style="background-color:#FAD090" value="<?php echo $claimsFilter;?>" onkeypress="submit_form(event);"/></td>

   	</tr>

    <tr>

    	<td width="125">Vehicle Color</td>

        <td></td>

         <td width="125">Bill To:</td>

        <td width="325">

        <select name="bill_to" style="background-color:#FAD090;width:225px">

             <option <?php if($bill_to==100 || !isset($_POST['bill_to'])){echo 'selected="selected"';}?> value="100">All</option>

             <option <?php if($bill_to==0){echo 'selected="selected"';}?> value="0">Owner</option>

             <?php

					$sql2 = "SELECT * FROM insurance_company WHERE id != 1 ORDER BY name asc";

					$rs2 = mysql_query($sql2);

					while($row2=mysql_fetch_array($rs2)){

						if($bill_to==$row2['id']){

							echo '<option value="'.$row2['id'].'" selected="selected">'.$row2['name'].'</option>';

						}

						else{

							echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';

						}

					}



					$sql2 = "SELECT * FROM clients ORDER BY name asc";

					$rs2 = mysql_query($sql2);

					while($row2=mysql_fetch_array($rs2)){

						if($bill_to==-1*$row2['id']){

							echo '<option value="-'.$row2['id'].'" selected="selected">'.$row2['name'].'</option>';

						}

						else{

							echo '<option value="-'.$row2['id'].'">'.$row2['name'].'</option>';

						}

					}

			?>

             </select>

        </td>

        <td width="125">Claims Handler:</td>

        <td width="325">

        <select style="background-color:#FAD090" name="claimsAtt">

            	<option value=""></option>

        <?php

			$sql22 = "SELECT * FROM rental_request WHERE active=1";

			$rs22 = mysql_query($sql22);

			while($row22=mysql_fetch_array($rs22)){

				if($claimsAtt==$row22['id']){

					echo '<option selected="selected" value="'.$row22['id'].'">'.$row22['name'].'</option>';

				}

				else{

					echo '<option value="'.$row22['id'].'">'.$row22['name'].'</option>';

				}

			}

		?>

        </select>

        </td>

   	</tr>



    <tr>

    	<td width="125">District:</td>

        <td>

        <select name="district" style="background-color:#FAD090">

            	<option <?php if($district ==0){echo 'selected="selected"';}?> value="0">All</option>

                    <?php

						$sql3 = "SELECT * FROM districts order by district";

						$rs3 = mysql_query($sql3);

						while($row3=mysql_fetch_array($rs3)){

							if($row3['id'] == $district){

								echo '<option selected="selected" value="'.$row3['id'].'">'.$row3['district'].'</option>';

							}

							else{

								echo '<option value="'.$row3['id'].'">'.$row3['district'].'</option>';

							}

						}

					?>

          	</select>

        </td>

         <td width="125">Phone Number:</td>

        <td width="325">

        <input type="text" name="phone" style="background-color:#FAD090" value="<?php echo $phone;?>" onkeypress="submit_form(event);"/>

        </td>

        <td width="125">Client Number:</td>

        <td width="325">

        <input type="text" name="clientno" style="background-color:#FAD090" value="<?php echo $clientno;?>" onkeypress="submit_form(event);"/>

        </td>

   	</tr>



    <tr>

    	<td colspan="6">

            <input type="submit" name="filter" value="Filter" style=" width:100px"/>&nbsp;

            <input type="submit" name="claims" value="Claims Only" style=" width:100px"/>&nbsp;

            <input type="submit" name="servicecalls" value="Service Calls" style=" width:100px"/>&nbsp;

            <input type="submit" name="fleetservices" value="Fleet Services" style=" width:100px"/>&nbsp;

            <input type="submit" name="inspections" value="Inspections" style=" width:100px"/>&nbsp;

            <input type="submit" name="carparts" value="Car Parts" style=" width:100px"/>&nbsp;

            <input type="submit" name="towing" value="Towing" style=" width:100px"/>&nbsp;

            <input type="submit" name="clear" value="Clear" style=" width:100px"/>

       	</td>

  	</table>

     </form>

     <?php } //no need filter?>

      	</td></tr>

        <tr><td colspan="12">&nbsp;</td></tr>

        <tr>

        	<td class="top-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2+15;?>">Date</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2-15;?>">Job</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col3;?>">A Number</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Car</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>"><?php if( isset($_POST['fleetservices'])) {echo 'KM'; } else { echo 'Cov/Use';}?> </td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Location</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Attendee</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>"><?php if( isset($_POST['fleetservices'])) { echo 'Cost'; } else {echo 'Charged';} ?></td>

            <?php

				if( isset($_POST['fleetservices'])) {

					echo '<td class="top-center-child" colspan="2" style="background-color:#ECF65C;" width="'.($col4*2).'">Description</td>';

				}

				else{

			?>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Insured</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Picture</td>

            <?php } ?>

			<td class="top-right-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Status</td>

        </tr>

        <?php

			/*if(checkEXT()){



				$user_id = $_SESSION['user_id'];

				$sql2 = "SELECT attendee_id FROM users WHERE id='$user_id'";

				$rs2 = mysql_query($sql2);

				$row2 = mysql_fetch_array($rs2);

				$aid = $row2['attendee_id'];

				$sql = "SELECT * FROM service_req WHERE `delete` =0 AND `attendee_id` = '$aid' order by STR_TO_DATE( `opendt` , '%m-%d-%Y %k:%i' ) DESC, id DESC";

				$rs = mysql_query($sql);

				$num_rows = mysql_num_rows($rs);

				$end = ($paging * $page)-1;

				$current_pt = ($paging * $page) - $paging;

				$sql = "SELECT * FROM service_req WHERE `delete` =0 AND `attendee_id` = '$aid' order by STR_TO_DATE( `opendt` , '%m-%d-%Y %k:%i' ) DESC, id DESC LIMIT ".$current_pt.", ".$paging;

				$rs = mysql_query($sql);

			}*/

			if(1){

				$filter = '';

				if($fstatus != 0){

					$filter = $filter.' AND status = '.$fstatus;

				}

				if($fjob != 0 && !isset($_POST['claims']) && !isset($_POST['servicecalls']) && !isset($_POST['fleetservices']) && !isset($_POST['inspections']) && !isset($_POST['carparts']) && !isset($_POST['towing'])){

					$filter = $filter.' AND job = '.$fjob;

				}

				else if(isset($_POST['claims'])){

					$filter = $filter.' AND (job=20 OR job=29 OR job=7 OR job=18 OR job=23 OR job=28 OR job=8 OR job=31 OR job=34 OR job=15 OR job=56 or job=57 or job=63)';

				}

				else if(isset($_POST['servicecalls'])){

					$filter .= " AND (job=6 OR job=2 OR job=1 OR job=5 OR job=4 OR job=41 OR job=9)";

				}

				else if(isset($_POST['fleetservices'])){

					$filter .= " AND (job=24 OR job=21 OR job=47)";

				}

				else if(isset($_POST['inspections'])){

					$filter .= " AND (job=11 OR job=10 OR job=40 OR job=37)";

				}

				else if(isset($_POST['carparts'])){

					$filter .= " AND (job=27 OR job=44)";

				}

				else if(isset($_POST['towing'])){

					$filter .= " AND (job=29 OR job='12' OR job=33 OR job=32 OR job=20 OR job=36 OR job=16 OR job=22 OR job=13 OR job=39 OR job=3 OR job=14 OR job=17)";

					//$filter .= " AND (job=12)";

				}



				if($faid != 0){

					$filter = $filter.' AND attendee_id = '.$faid;

				}

				if($sid != 0){

					$filter = $filter.' AND id = '.$sid;

				}



				if($adj_fee == 2){

					$filter = $filter.' AND `adjfee`=0';

				}

				else if($adj_fee == 1){

					$filter = $filter.' AND `adjfee`!=0';

				}



				if($nrs_fee == 2){

					$filter = $filter.' AND `accfee`=0';

				}

				else if($nrs_fee == 1){

					$filter = $filter.' AND `accfee`!=0';

				}



				if($rhd ==1){

					$filter = $filter.' AND `RightHandDrive`= 1';

				}

				else if($rhd==2){

					$filter = $filter.' AND `RightHandDrive`=0';

				}



				if($claimsFilter !== ''){

					$filter = $filter." AND `claimNo` = '$claimsFilter'";

				}

				if($phone !==''){



					if(!strstr($phone,'-')){

						$temp = substr($phone,0,3)."-".substr($phone,3);

						//echo $temp;

					}

					else{

						$temp = str_replace("-","",$phone);

					}

					$filter = $filter." AND (`AddPhone` = '$phone' OR `AddPhone`= '$temp')";

				}

				if($claimsAtt!=''){

					$filter = $filter." AND `claimsAttId` = '$claimsAtt'";

				}

				if($persoonsFilter !== ''){

					$sql4 = "SELECT * FROM `drivers_license` WHERE `persoonsNo`='$persoonsFilter'";

					$rs4 = mysql_query($sql4);



					if(mysql_num_rows($rs4) != 0){

						$temp = ' AND (';

						$temp_f = 1;

						while($row4 = mysql_fetch_array($rs4)){

							if($temp_f ==1){

								$temp = $temp." `licenseNo`='".$row4['id']."'  OR licenseNo2='".$row4['id']."'";

								$temp_f = 0;

							}

							else{

								$temp = $temp." OR `licenseNo`='".$row4['id']."'  OR licenseNo2='".$row4['id']."'";

							}

						}

						$temp = $temp.')';

					}

					else{

						//nothing found

						$temp = " AND `licenseNo` = '-1'";

					}

					//echo $temp;

					$filter = $filter.$temp;

				}

				if($admFilter !== ''){

					$sql4 = "SELECT * FROM `drivers_license` WHERE `admNo`='$admFilter'";

					$rs4 = mysql_query($sql4);



					if(mysql_num_rows($rs4) != 0){

						$temp = ' AND (';

						$temp_f = 1;

						while($row4 = mysql_fetch_array($rs4)){

							if($temp_f ==1){

								$temp = $temp." `licenseNo`='".$row4['id']."'  OR licenseNo2='".$row4['id']."'";

								$temp_f = 0;

							}

							else{

								$temp = $temp." OR `licenseNo`='".$row4['id']."'  OR licenseNo2='".$row4['id']."'";

							}

						}

						$temp = $temp.')';

					}

					else{

						//nothing found

						$temp = " AND `licenseNo` = '-1'";

					}

					//echo $temp;

					$filter = $filter.$temp;

				}

				if(strcmp($sdate,'')!=0){

					$filter = $filter." AND STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('".$sdate."','%m-%d-%Y') ";

				}

				if(strcmp($edate,'')!=0){

					$filter = $filter." AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('".$edate."','%m-%d-%Y')";

				}

				if(strcmp($anumber,'')!=0){

					$filter = $filter." AND a_number = '$anumber'";

				}



				if(strcmp($licenseNo,'')!=0){

					$filter = $filter." AND (  licenseNo = '$licenseNo' OR licenseNo2='$licenseNo')";

				}



				if($bill_to!=100){

					$filter = $filter." AND bill_to = '$bill_to'";

				}



				if($mr != 0){

					if($mr == 1){

						$filter = $filter." AND `money_delivered` != 0 AND `charged` > 0";

					}

					else{ //$mr == -1

						//$filter = $filter." AND `money_delivered` = 0 AND `charged` > 0 AND paymentType != 'Bank Transfer' AND paymentType!= 'Office' AND paymentType!= 'Agreement' AND `po_received`!=1";

						$filter = $filter." AND `money_delivered` = 0 AND `charged` > 0 AND paymentType!= 'Agreement' AND `po_received`!=1";

					}

				}

				if(strcmp($clientno,'')!=0){

					$filter = $filter." AND `ClientNo` = '$clientno'";

				}

				if(strcmp($vin,'')!=0){

					$filter = $filter." AND `vin` = '$vin'";

				}



				if($insured_not != 0){

					if($insured_not == 2){

						$filter = $filter." AND `insured` = 0";

					}

					else{

						$filter = $filter." AND `insured` = 1";

					}

				}



				if($district!=0){

					$filter = $filter." AND `district` = '$district'";

				}

				if($policyFilter!==''){

					$filter = $filter." AND `pol` = '$policyFilter'";

				}



				if(isset($_POST[vehicle_model]) && $_POST[vehicle_model]!==''){

					$t = $_POST['vehicle_make'].' '.$_POST['vehicle_model'];

					$filter .= " AND `car` LIKE '$t'";

				}

				else{

					unset($_POST[vehicle_model]);

					unset($_POST[vehicle_make]);

					unset($_POST[vehicle_year]);

				}





				$sql4 = "SELECT * FROM users WHERE id='".$_SESSION['user_id']."'";

				$rs4 = mysql_query($sql4);

				$row4 = mysql_fetch_array($rs4);

				if($row4['clientId']!=0){

					$filter = $filter." AND bill_to='".$row4['clientId']."'";

				}



				if ( $licenseNo!=='' ){



				}

				else if(!isset($_POST['fleetservices']) && $fjob != 35 && $fjob != 21 && $fjob != 24){

					$filter .= " AND job!=24 AND job!=21 AND job!=35 ";

				}

				else if($fjob != 35 ){

					//$filter .= " AND job!=35 ";

				}





				$sql = "SELECT * FROM service_req WHERE `delete` =0 ".$filter." order by STR_TO_DATE( `opendt` , '%m-%d-%Y %k:%i' ) DESC, id DESC";

				$rs = mysql_query($sql);

				$num_rows = mysql_num_rows($rs);

				$end = ($paging * $page)-1;

				$current_pt = ($paging * $page) - $paging;

				$sql = "SELECT * FROM service_req WHERE `delete` =0 ".$filter." order by STR_TO_DATE( `opendt` , '%m-%d-%Y %k:%i' ) DESC, id DESC LIMIT ".$current_pt.", ".$paging;

				$rs = mysql_query($sql);

			}

			$bg = 0;

			while($row = mysql_fetch_array($rs)){

				$sql11="SELECT * FROM `service_req_extra` WHERE `sc_id`=".$row['id'];

				$rs11=mysql_query($sql11);

				$row11=mysql_fetch_array($rs11);

				//echo mysql_error();



		?>

        	<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>

                <td class="middle-left-child" <?php



				$ids='';

				if($row['status']==19 && $row['mi']){

					$ids='id="flashg_'.$flashg.'"';

					$flashg++;

				}

				else if($row['status']==19 || $row['status']==12 || $row['status']==31){

					$ids='id="flash_'.$flash.'"';

					$flash++;

				}



				if($row11['status']==='Not At Fault'){

					echo 'style="background-color:#6F3"';

				}

				else if($row['status']==4){

					echo 'style="background-color:#EB6C2C"';

				}

				else if( $row11['status']==='At Fault' ||  $row11['status']==='Shared Liability'){

					echo 'style="background-color:#FF0000"';

				}

				else if($row['charged']>0 && $row['money_delivered'] == 0 && $row['status'] != 3){

					echo 'style="background-color:#800080"';

				}



				$news = '';

				$new = 0;

				if($row['status']==7){

					$new=1;

					$news='style="background-color:#FF0000"';

				}

				if($row['status']==8){ //In Progress

					$new=1;

					$news='style="background-color:#00FF00"';

				}

				if($row['status']==13){ //In Progress

					$new=1;

					$news='style="background-color:#FF0099"';

				}



				?> <?php if($bg==0){ echo 'style="background-color:#E1DDDC" ';}?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['id'];?>"><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></a></td>

                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2+10;?>"><?php echo substr($row['opendt'],0,16);?></td>

                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2-15;?>"><?php

					$jid = $row['job'];

					$sql2 = "SELECT * FROM jobs where id = '$jid'";

					$rs2 = mysql_query($sql2);

					$row2 = mysql_fetch_array($rs2);

					$e='';

					if($row11['car_driveable']!=='Driveable'){

						$e='<br/><span style="color:blue">'.$row11['car_driveable'].'</span>';

					}

					echo $row2['description'].$e;

				?></td>

                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>"><?php echo $row['a_number'];?></td>

             	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2;?>"><?php echo $row['car'];?></td>

                 <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>">

                	<?php

					if( isset($_POST['fleetservices'])) {

						echo $row['km'];

					}

					else{

						$PolicyNo = $row['pol'];

						$LicPlateNo = $row['a_number'];

						$veh_year=$row['veh_year'];

						$extra='';



						if($veh_year!=0){

							$extra='<br/><span style="color:blue">'.$veh_year.'</span>';

						}

						/*$sql3 = "SELECT * FROM `vehicles_2` WHERE `PolicyNo`='$PolicyNo' AND `LicPlateNo`='$LicPlateNo'";

						$rs3 = mysql_query($sql3);

						$row3 = mysql_fetch_array($rs3);*/

						$sql3 = "SELECT VehCoverage, VehUse FROM VW_VEHICLE WHERE PolicyNo='$PolicyNo' AND LicPlateNo='$LicPlateNo'";

						$params = array();

						//$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

						//$rs3 = sqlsrv_query($conn,$sql3,$params,$options);

						$rs3 = mssql_query($sql3);

						if($row3 = mssql_fetch_array($rs3)){

							echo $row3['VehCoverage'].'/'.$row3['VehUse'].$extra;



						}

						else{

							$sql3 = "SELECT * FROM `non_client_extra` WHERE `id` = '$LicPlateNo'";

							$rs3 = mysql_query($sql3);

							$row3 = mysql_fetch_array($rs3);

							$veh_year=$row3['year'];

							if($veh_year!=0){

								$extra='<br/><span style="color:blue">'.$veh_year.'</span>';

							}

							if($row3){

								echo $row3['vehicle_coverage'].'/'.$row3['vehicle_use'].$extra;

							}

						}

					}



					?>

                </td>

                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2;?>"><?php echo $row['location'];?></td>



                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>"><?php

					$aid = $row['attendee_id'];

					$sql2 = "SELECT * FROM attendee where id = '$aid'";

					$rs2 = mysql_query($sql2);

					$row2 = mysql_fetch_array($rs2);

					echo $row2['s_name'];

				?></td>

               	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>">

                	<?php

					if( isset($_POST['fleetservices'])) {

						echo number_format($row['cost'],2);

					}

					else{

					if($row['charged']>0) echo '<b>'.number_format($row['charged'],2).'</b>';else{echo number_format($row['charged'],2);}

					if(strlen($row['receipt'])>0){

						echo ' <br/><a style="color:#0000FF; font-weight: bold;">'.$row['receipt'].'</a>';

					}

					}

					?>

                </td>

                <?php

				if( isset($_POST['fleetservices'])) {

					if( $row['job_id']==21 ){

						$sql5="SELECT * FROM `vehicle_maintenance_type` WHERE `id`=".$row['ms_type'];

						$rs5=mysql_query($sql5);

						$row5=mysql_fetch_array($rs5);

					}

					else{

						$sql5="SELECT * FROM `vehicle_service_type` WHERE `id`=".$row['ms_type'];

						$rs5=mysql_query($sql5);

						$row5=mysql_fetch_array($rs5);

					}

					 if($bg==0){

						 echo '<td class="middle-none-child" style="background-color:#E1DDDC" colspan="2" width="'.($col4*2).'">'.$row5['type'].'</td>';

					 }

					 else{

						echo '<td class="middle-none-child" colspan="2" width="'.($col4*2).'">'.$row5['type'].'</td>';

					 }

				}

				else{

			?>

              	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>"><?php

                	if($row['insured']){

						echo 'Yes';

					}

					else{

						echo 'No';

					}

				?></td>

               <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>">

                	<?php



						if(file_exists(FOLDER."rrimage/".$row['id']) || file_exists(FOLDER."rrimage/".$row['accident_link']) || file_exists(FOLDER."rrimage/".$row['accident_link2']) || file_exists(FOLDER."rrimage/".$row['accident_link3']) || file_exists(FOLDER."rrdocs/".$row['id']) || file_exists(FOLDER."rrdocs/".$row['accident_link'])){

							echo '<b>Yes</b>';

						}

						else{

							echo 'No';

						}

					?>

                </td>

                <?php } ?>

                <td class="middle-right-child" <?php if($new==1){echo $news;} else if($bg==0){ echo 'style="background-color:#E1DDDC"';} echo ' '.$ids;?> width="<?php echo $col4;?>"><?php

					$sid = $row['status'];

					$sql2 = "SELECT * FROM status WHERE id = '$sid'";

					$rs2 = mysql_query($sql2);

					$row2 = mysql_fetch_array($rs2);

					echo $row2['status'];

				?>

                </td>

        	</tr>

        <?php

			$current_pt++;

			if($bg == 0){

				$bg = 1;

			}

			else{

				$bg = 0;

			}

			}

		?>

        <tr><td class="middle-child" colspan="12" align="right"><div class="buttonwrapper" align="right">

					<a class="squarebutton" target="_blank" href="print_statement.php?id=<?php echo encrypt($filter, enc_key)?>"><span>Print Statement</span></a>

				</div></td></tr>

        <tr>

        	<td class="bottom-child" colspan="12">Page

            	<?php

					$cur_page = $page;

					if($page - $page_dif < 1){

						$start_page = 1;

					}

					else{

						$start_page = $page - $page_dif;

						$less = $start_page - 1;

					}



					while($start_page < ($page + $page_dif) && ($num_rows > (($start_page-1)*$paging))){ //add pages



						if($start_page == ($page + $page_dif - 1)){

							echo '<a style="color:#DCB272" href="/index.php?page='.$start_page.'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'&district='.$district.'&nrs_fee='.$nrs_fee.'&adj_fee='.$adj_fee.'">'.$start_page.'</a> , <a style="color:#DCB272" href="/roadservice/index.php?page='.($start_page+1).'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'&district='.$district.'&nrs_fee='.$nrs_fee.'&adj_fee='.$adj_fee.'&clientno='.$clientno.'&bill_to='.$bill_to.'&anumber='.$anumber.'"> More</a>';

						}

						else if($page == $start_page){

							echo '<<a style="color:#DCB272; font-weight: bold" href="/index.php?page='.$start_page.'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'&district='.$district.'&nrs_fee='.$nrs_fee.'&adj_fee='.$adj_fee.'&clientno='.$clientno.'&bill_to='.$bill_to.'&anumber='.$anumber.'">'.$start_page.'</a>> , ';

						}

						else if ($less > 1){

							echo '<a style="color:#DCB272" href="/index.php?page='.($less).'"> Less</a> , <a style="color:#DCB272" href="/index.php?page='.$start_page.'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'&district='.$district.'&nrs_fee='.$nrs_fee.'&adj_fee='.$adj_fee.'&clientno='.$clientno.'&bill_to='.$bill_to.'&anumber='.$anumber.'">'.$start_page.'</a> , ';

							$less = 0;

						}

						else{

							echo '<a style="color:#DCB272" href="/index.php?page='.$start_page.'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'&district='.$district.'&nrs_fee='.$nrs_fee.'&adj_fee='.$adj_fee.'&clientno='.$clientno.'&bill_to='.$bill_to.'&anumber='.$anumber.'">'.$start_page.'</a> , ';

						}

						$start_page++;

					}

				?>&nbsp; Number of Results: <?php echo $num_rows;?>

                <?php mssql_close($connt);?>

            </td>

        </tr>

 </table>

<script type="text/javascript">

	function submit_form(e){

		if(window.event){

			keycode = window.event.keyCode;

		}

  		else if (e){

			keycode = e.which;

		}

		else{

			return false;

		}

		if(keycode == 13){

			document.forms["filter"].submit();

		}

	}





	$(document).ready(function(){

     	var wcol="red";

		var gcol="#00FF00";

	    setInterval(function() {

            if(wcol=="red"){

			wcol="white";

			}

			else{

				wcol="red";

			}

			if(gcol=="#00FF00"){

				gcol="white";

			}

			else{

				gcol="#00FF00";

			}

			color(wcol);

			gcolor(gcol);

        }, 500);



		$("select#vehicle_year").change(function(){

		var vyear = $("select#vehicle_year option:selected").attr('value');

		$.post("select_make.php", {"vyear":vyear},function(data){

			$("select#vehicle_make").html(data);

			var vmake = $("select#vehicle_make option:selected").attr('value');

			$.post("select_model.php", {"vyear":vyear,"vmake":vmake},function(data){

			$("select#vehicle_model").html(data);

			var vmodel = $("select#vehicle_model option:selected").attr('value');

			});

			});

	});



	$("select#vehicle_make").change(function(){

	 	var vyear = $("select#vehicle_year option:selected").attr('value');

		var vmake = $("select#vehicle_make option:selected").attr('value');

		$.post("select_model.php", {"vyear":vyear,"vmake":vmake},function(data){

			$("select#vehicle_model").html(data);

			var vmodel = $("select#vehicle_model option:selected").attr('value');

			});

	});





    });



	function color(col){

		var fl=<?php echo $flash;?>;

		for(var i=1; i<fl; i++) {

			document.getElementById('flash_'+i).style.backgroundColor =col;

		}

 	}

	function gcolor(col){

		var fl=<?php echo $flashg;?>;

		for(var i=1; i<fl; i++) {

			document.getElementById('flashg_'+i).style.backgroundColor =col;

		}

 	}

</script>

</body>

</html>
