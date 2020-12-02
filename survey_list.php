<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include 'dbc.php';

date_default_timezone_set('America/Aruba');

page_protect();

include "support/connect.php";

include "support/function.php";

session_start();

if(isPolice()){

	header('location:index.php');

}



$page = $_REQUEST['page'];

if(!$page){

	$page = 1;

}



if($_POST['s_id'] && $_POST['s_id']!==''){

	header('location:survey.php?sid='.$_POST['s_id']);

}



$paging = 30; //items per page

$page_dif = 5;

$col1 = 45;

$col2 = 132;

$col3 = 75;

$col4 = 50;



if($_REQUEST['adjuster']){

	$adjuster = $_REQUEST['adjuster'];

}

else{

	$adjuster = 0;

}



if($_REQUEST['policy_number']){

	$policy_number = $_REQUEST['policy_number'];

}

else{

	$policy_number = '';

}



if($_REQUEST['license_plate']){

	$license_plate = $_REQUEST['license_plate'];

}

else{

	$license_plate = '';

}



if($_REQUEST['claim_number']){

	$claim_number = $_REQUEST['claim_number'];

}

else{

	$claim_number = '';

}



if($_REQUEST['survey_duration']){

	$survey_duration = $_REQUEST['survey_duration'];

}

else{

	$survey_duration = 0;

}



if($_REQUEST['vin']){

	$vin = $_REQUEST['vin'];

}

else{

	$vin = '';

}



if($_REQUEST['claimshandler']){

	$claimshandler = $_REQUEST['claimshandler'];

}

else{

	$claimshandler = 0;

}



if($_REQUEST['status']){

	$status = $_REQUEST['status'];

}

else{

	$status = 0;

}

if($_REQUEST['sdate']){

	$sdate = $_REQUEST['sdate'];

}

else{

	$sdate='';

}

if($_REQUEST['edate']){

	$edate = $_REQUEST['edate'];

}

else{

	$edate='';

}



$car_driveable=$_POST['car_driv'];





if(strcmp($_REQUEST['clear'],'Clear') == 0){ //Clear filter

	$adjuster = 0;

	$policy_number = '';

	$license_plate = '';

	$sdate = '';

	$edate = '';

	$claim_number = '';

	$survey_duration = 0;

	$vin = '';

	$claimshandler = 0;

	$status = 0;

	$sdate = '';

	$edate = '';

	$car_driveable=-1;

	$_POST['bodyshop']=0;

}



echo menu();



if(isset($_POST['clear'])){

 header('location:survey_list.php');

}



$filter = '';

if(trim($_REQUEST['adjuster'])!=0){

	$filter=$ilter." AND `adjuster_id`=".$adjuster." ";

}

if(trim($policy_number)!==''){

	$filter=$filter." AND `pol`='".$policy_number."'";

}

if(trim($license_plate)!==''){

	$filter=$filter." AND `a_number`='".$license_plate."'";

}

if(trim($claim_number)!==''){

	$filter=$filter." AND `claimNo`='".$claim_number."'";

}

if(trim($status)!=0){

	$filter=$filter." AND `status_id`='".$status."'";

}

if($claimshandler!=0){

	$filter=$filter." AND `claimsAttId`=".$claimshandler."";

}

if($sdate!==''){

	$filter = $filter." AND STR_TO_DATE(`open_time`,'%m-%d-%Y') >= STR_TO_DATE('".$sdate."','%m-%d-%Y') ";

}

if($edate!==''){

	$filter = $filter." AND STR_TO_DATE(`open_time`,'%m-%d-%Y') <= STR_TO_DATE('".$edate."','%m-%d-%Y') ";

}

if($car_driveable!=0){

	if($car_driveable==-1){

		$temp='Not Driveble';

	}

	else{

		$temp='Driveable';

	}

	$filter.= " AND `car_driveable`='$temp'";

}

if($_POST['bodyshop']!=0){

	$filter .= " AND `bodyshop_id`='".$_POST['bodyshop']."' ";

}













?>



<table width="1200" cellspacing="0">

    	<tr>

        	<td colspan="12" align="center" style="border:0;color:#148540"><h8 style="color:#FFF">Survey List</h8></td>

        </tr>

         <tr><td colspan="12">&nbsp;</td></tr>

         <form name="filter" action="" method="post">

         <tr>

         	<td class="child" colspan="12">

            	<table width="100%">

                <tr>

                	<td width="10%">Adjuster:</td>

                	<td width="10%"><select name="adjuster" style="background-color:#FAD090"  >

                    <option value="0"></option>

                    <?php

						$sqlt = "SELECT * FROM `adjuster`";

						$rst=mysql_query($sqlt);

						while($rowt=mysql_fetch_array($rst)){

							if($rowt['id']==$_REQUEST['adjuster']){

								echo '<option value="'.$rowt['id'].'" selected="selected">'.$rowt['name'].'</option>';

							}

							else{

								echo '<option value="'.$rowt['id'].'">'.$rowt['name'].'</option>';

							}

						}

					?>

                    </select>

                    </td>

                    <td width="10%">Policy Number:</td>

                	<td width="10%"><input type="text" name="policy_number" style="background-color:#FAD090"  value="<?php echo $_POST['policy_number'];?>"/></td>



            	 	<td colspan="7">

                    Date From: <input type="text" id="sdate" name="sdate" readonly/ size="13" value="<?php echo $sdate;?>"/>

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

              </script></td>

        		 </tr>

                 <tr>

                 	<td width="10%">License Plate:</td>

                	<td width="10%"><input type="text" name="license_plate" style="background-color:#FAD090"  value="<?php echo $_POST['license_plate'];?>" onkeypress="submit_form(event);"/></td>

                    <td width="10%">Claim Number:</td>

                	<td width="10%"><input type="text" name="claim_number" style="background-color:#FAD090"  value="<?php echo $_POST['claim_number'];?>"/></td>

                     <td width="10%">Survey Duration:</td>

                	<td width="10%"><select name="survey_duration" style="background-color:#FAD090">

                    	<option value="0"></option>

                    	<option value="1" <?php if($_REQUEST['survey_duration']==1) {echo 'selected="selected"';}?>>1-5 Working Days</option>

                        <option value="2" <?php if($_REQUEST['survey_duration']==2) {echo 'selected="selected"';}?>>6-8 Working Days</option>

                        <option value="3" <?php if($_REQUEST['survey_duration']==3) {echo 'selected="selected"';}?>>>8 Working Days</option>

                    </select>

                    </td>

                 	<td colspan="5">

                    <table>

                    	<tr>

                        	<td width="180px">&nbsp;</td>

                        	<td width="60px" style="background-color:#5C0100">&nbsp;</td>

                        </tr>

                        <tr>

                        	<td width="180px">&nbsp;</td>

                        	<td width="60px" style="background-color:#FF1E02">&nbsp;</td>

                        </tr>

                    </table></td>

                	</tr>

                <tr>

                  <tr>

                 	<td width="10%">Type Of Damage:</td>

                	<td width="10%">

                     <select name="rep_damage" id="rep_damage" style="background-color:#FAD090;">

            	<option id="0"></option>

                <?php

					$sql5="SELECT * FROM `vehicle_damage` order by `id`";

					$rs5=mysql_query($sql5);

					while($row5=mysql_fetch_array($rs5)){

						if($_REQUEST['rep_damage']==$row5['id']){

							echo '<option value="'.$row5['id'].'" selected="selected">'.$row5['description'].'</option>';

						}

						else{

							echo '<option value="'.$row5['id'].'">'.$row5['description'].'</option>';

						}

					}

				?>

            </select>

                    </td>

                    <td width="10%">Claim Handler:</td>

                	<td width="10%">

                    <select name="claimshandler" style="background-color:#FAD090" >

                    <option value="0"></option>

                    <?php

						$sqlt = "SELECT * FROM `rental_request` WHERE `active`=1";

						$rst=mysql_query($sqlt);

						while($rowt=mysql_fetch_array($rst)){

							if($rowt['id']==$_POST['claimshandler']){

								echo '<option value="'.$rowt['id'].'" selected="selected">'.$rowt['name'].'</option>';

							}

							else{

								echo '<option value="'.$rowt['id'].'">'.$rowt['name'].'</option>';

							}

						}

					?>

                    </select>

                    </td>

                     <td width="10%">Status:</td>

                	<td width="10%"><select name="status" style="background-color:#FAD090">

                    	<option value="0"></option>

                      <?php

						$sqlt = "SELECT * FROM `status_survey`";

						$rst=mysql_query($sqlt);

						while($rowt=mysql_fetch_array($rst)){

							if($rowt['id']==$_REQUEST['status']){

								echo '<option value="'.$rowt['id'].'" selected="selected">'.$rowt['status'].'</option>';

							}

							else{

								echo '<option value="'.$rowt['id'].'">'.$rowt['status'].'</option>';

							}

						}

					?>

                    </select>

                    </td>

                 	<td colspan="5">

                    	<table>

                    	<tr>

                        	<td width="180px">&nbsp;</td>

                        	<td width="60px" style="background-color:#FF8E02">&nbsp;</td>

                        </tr>

                        <tr>

                        	<td width="180px">&nbsp;</td>

                        	<td width="60px" style="background-color:#65FFA9">&nbsp;</td>

                        </tr>

                    </table>

                    </td>

                	</tr>

              	<tr>

                  <tr>

                 	<td width="10%">Car Driveable:</td>

                	<td width="10%">

                     <select name="car_driv" id="car_driv" style="background-color:#FAD090;">

            	<option value="0" <?php if($_POST['car_driv']==0){ echo 'selected="selected"';}?>></option>

                <option value="1" <?php if($_POST['car_driv']==1){ echo 'selected="selected"';}?>>Yes</option>

                <option value="-1" <?php if($_POST['car_driv']==-1){ echo 'selected="selected"';}?>>No</option>

            </select>

                    </td>

                    <td width="10%">Bodyshop:</td>

                	<td width="10%">

                    <select name="bodyshop" style="background-color:#FAD090" >

                    <option value="0" <?php if($_POST['bodyshop']==0){ echo 'selected="selected"';}?>></option>

                    <?php

						$sqlt = "SELECT * FROM `bodyshop` WHERE `active`=1";

						$rst=mysql_query($sqlt);

						while($rowt=mysql_fetch_array($rst)){

							if($rowt['id']==$_POST['bodyshop']){

								echo '<option value="'.$rowt['id'].'" selected="selected">'.$rowt['name'].'</option>';

							}

							else{

								echo '<option value="'.$rowt['id'].'">'.$rowt['name'].'</option>';

							}

						}

					?>

                    </select>

                    </td>

                     <td width="10%">id:</td>

                	<td width="10%"><input type="text" name="s_id" style="background-color:#FAD090" size="8"/>

                    </td>

                 	<td colspan="5">

                    <table>

                    	<tr>

                        	<td width="180px">&nbsp;</td>

                        	<td width="60px" style="background-color:#0058FE">&nbsp;</td>

                        </tr>

                        <tr>

                        	<td width="180px">&nbsp;</td>

                        	<td width="60px" style="background-color:#09018B">&nbsp;</td>

                        </tr>

                    </table>

                    </td>

                	</tr>

                <tr>

               	  <td colspan="6"><input type="submit" name="filter" value="Filter" style=" width:100px"/>&nbsp;&nbsp;<input type="submit" name="clear" value="Clear" style=" width:100px"/>

                    </td>

                    <td colspan="5">

                    <table>

                    	<tr>

                        	<td width="180px">&nbsp;</td>

                        	<td width="60px" style="background-color:#7E0084">&nbsp;</td>

                        </tr>

                        <tr>

                        	<td width="180px">&nbsp;</td>

                        	<td width="60px" style="background-color:green">&nbsp;</td>

                        </tr>

                    </table>

                    </td>

                </tr>

                </table>

            </td>

         </tr>



         </form>

        <tr><td colspan="12">&nbsp;</td></tr>

        <tr>

        	<td class="top-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2+15;?>">Date Requested<br/>Date Closed</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col3;?>">License Plate</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Car</td>

            <td colspan="2" class="top-center-child" style="background-color:#ECF65C;" width="<?php echo ($col4-15+$col2);?>">Location Survey</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo ($col3);?>">Claims Handler</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Claims No</td>

                        <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Date of Loss</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">NRC</td>

			<td  class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Adjuster</td>

            <td  class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">SD</td>

            <td class="top-right-child" style="background-color:#ECF65C;" width="<?php echo $col4?>">Status</td>



        </tr>

        <?php

			if(1){



				$sql = "SELECT s.id, s.service_req_id, s.open_time, s.close_time, s.location, s.adjuster_id, s.status_id FROM `survey` AS s left join `service_req` AS r on s.service_req_id = r.id left join `service_req_extra` AS e ON e.sc_id=r.id WHERE s.`active`=1 ".$filter." order by STR_TO_DATE( s.`open_time` , '%m-%d-%Y %k:%i' ) DESC, s.id DESC";

				$rs = mysql_query($sql);

				$num_rows = mysql_num_rows($rs);

				$end = ($paging * $page)-1;

				$current_pt = ($paging * $page) - $paging;

				$sql = "SELECT  s.id, s.service_req_id, s.open_time, s.close_time, s.location, s.adjuster_id, s.status_id, s.est_report_t, s.neg_review_t, s.parts_prices_t, s.quotation_t, s.parts_list_t, s.survey_vehicle_t, s.calculation_t, s.survey_type_id FROM `survey` AS s left join `service_req` AS r on s.service_req_id = r.id left join `service_req_extra` AS e ON e.sc_id=r.id WHERE s.`active`=1 ".$filter." order by STR_TO_DATE( s.`open_time` , '%m-%d-%Y %k:%i' ) DESC, s.id DESC LIMIT ".$current_pt.", ".$paging;

				$rs = mysql_query($sql);

			}

			$bg = 0;

			while($row = mysql_fetch_array($rs)){

				$tid=$row['service_req_id'];

				$sql4="SELECT * FROM `service_req` WHERE `id`='$tid'";

				$rs4=mysql_query($sql4);

				$row4=mysql_fetch_array($rs4);



				$sql5="SELECT * FROM `rental` WHERE `service_req_id`='$tid'";

				$rs5=mysql_query($sql5);

				$row5=mysql_fetch_array($rs5);



				$sql6="SELECT * FROM `service_req_extra` WHERE `sc_id`='$tid'";

				$rs6=mysql_query($sql6);

				$row6=mysql_fetch_array($rs6);



				$sqlt="SELECT * FROM vehicle_damage WHERE id='".$row6['rep_damage']."'";

				$rst=mysql_query($sqlt);

				$rowt=mysql_fetch_array($rst);

				$damage=$rowt['description'];



				list($month,$day,$year) = explode('-',substr($row['open_time'],0,10));





				$sql7 = "SELECT VehCoverage, VehUse FROM VW_VEHICLE WHERE LicPlateNo = '".$row4['a_number']."' AND PolicyNo LIKE '".$row4['pol']."' ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Renewal DESC, PolicyNo DESC";

				$params = array();

				//$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

				//$rs7 = mssql_query($conn,$sql7,$params,$options);
				$rs7 = mssql_query($sql7);

				$row7 = mssql_fetch_array($rs7);



					list($date,$time) = explode(' ',$row['open_time']);

					$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

					if($row['close_time']!==''){

						list($month,$day,$year) = explode('-',substr($row['close_time'],0,10));

						list($date,$time) = explode(' ',$row['close_time']);

						$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

					}

					else{

						$date2 = new DateTime(date("Y-m-d H:i"));

					}

					$day_diff=0;

					$interval = $date1->diff($date2);

					$day_diff=$interval->days;

					$date1 = new DateTime($year.'-'.$month.'-'.$day);

					if($row['close_time']!==''){

						list($month,$day,$year) = explode('-',substr($row['close_time'],0,10));

						list($date,$time) = explode(' ',$row['close_time']);

						$date2 = new DateTime($year.'-'.$month.'-'.$day);



					}

					else{

						$date2 = new DateTime(date("Y-m-d"));

					}

					$period = new DatePeriod($date1, new DateInterval('P1D'), $date2);

					$holidays = array('2015-12-25');

					foreach($period as $dt) {



						$curr = $dt->format('D');

						if (in_array($dt->format('Y-m-d'), $holidays)) {

						   $day_diff--;

						}

						if ($curr=='Sat' || $curr=='Sun') {

							$day_diff--;



						}

					}



				$dur=0;

				if($day_diff>=1 && $day_diff<=5){

					$dur=1;

				}

				else if($day_diff>=6 && $day_diff<=8){

					$dur=2;

				}

				else if($day_diff>=9){

					$dur=3;

				}



				if( ($_REQUEST['rep_damage']==0 || $_REQUEST['rep_damage']==$row6['rep_damage']) && ($_REQUEST['survey_duration']==0 || $_REQUEST['survey_duration']==$dur)){



		?>

        	<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>

                <td class="middle-left-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="survey.php?sid=<?php echo $row['id'];?>"><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?>

                </a>

                <?php

					if($row['survey_type_id']==2){

						echo '<br/>Sub';

					}

				?>

                </td>

                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2+10;?>"><?php echo substr($row['open_time'],0,16).'<br/><a style="color:blue">'.substr($row['close_time'],0,16).'</a>';?></td>

             	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>"><?php

					if($row4['insured']==1){

						echo '<a style="color:green; font-weight:bold">'.$row4['a_number'].'</a>';

					}

					else{

						echo '<a style="color:red; font-weight:bold">'.$row4['a_number'].'</a>';

					}

				?></td>

                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2;?>"><?php

					echo $row4['car'];

				?></td>

                <td colspan="2" class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo ($col4-15+$col2);?>"><?php

					echo $row['location'];



				?></td>

                	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>">

                <?php

					$sql3 = "SELECT * FROM rental_request WHERE id = '".$row4['claimsAttId']."'";

					$rs3 = mysql_query($sql3);

					$row3 = mysql_fetch_array($rs3);

					echo $row3['name'];



				?>

              </td>

              <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo ($col3);?>"><?php echo $row4['claimNo'].'<br/><a style="color:blue">'.$damage.'</a>';?></td>

              <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2;?>">

                <?php



					list($month,$day,$year) = explode('-',substr($row4['opendt'],0,10));

					$date1 = new DateTime($year.'-'.$month.'-'.$day);

					if($row7['VehCoverage']){

						if($row7['VehCoverage']==='SC'){

							$e=	'<span style="color:#ff0000; font-weight:bold">'.$row7['VehCoverage'].'/'.$row7['VehUse'].'</span>';

						}

						else if($row7['VehCoverage']==='C'){

							$e=	'<span style="color:#ff3300; font-weight:bold">'.$row7['VehCoverage'].'/'.$row7['VehUse'].'</span>';

						}

						else if($row7['VehCoverage']==='TPC'){

							$e=	'<span style="color:#0059b3; font-weight:bold">'.$row7['VehCoverage'].'/'.$row7['VehUse'].'</span>';

						}

						else if($row7['VehCoverage']==='TP'){

							$e=	'<span style="color:#4da6ff; font-weight:bold">'.$row7['VehCoverage'].'/'.$row7['VehUse'].'</span>';

						}

						else{

							$e=	$row7['VehCoverage'].'/'.$row7['VehUse'];

						}

					}

					else{

						$sql7="SELECT * FROM `non_client_extra` WHERE `id` = '".$row4['a_number']."'";

						$rs7=mysql_query($sql7);

						$row7=mysql_fetch_array($rs7);

						$e='<span style="color:#b3b3b3 ; font-weight:bold">'.$row7['vehicle_coverage'].'/'.$row7['vehicle_use'].'</span>';

					}

					echo date_format($date1,"d F, Y").'<br/>'.$e;



				?>

              </td>

              	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col1;?>">

				 <?php

					if($row5['id']){

						if($row5['status']!='Reservation'){

							$time_out = $row5['time_out'];

							$rate = $row5['rate'];

							$time_in = $row5['time_in'];

							list($month,$day,$year) = explode('-',substr($time_out,0,10));

							list($date,$time) = explode(' ',$time_out);

							$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

							$date2 = new DateTime(date("Y-m-d H:i"));

							if(strlen(trim($time_in))!=0){

								list($month,$day,$year) = explode('-',substr($time_in,0,10));

								list($date,$time) = explode(' ',$time_in);

								$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

							}

							$interval = $date1->diff($date2);



							$td=0;

							if($interval->h==0){

								$td=$td+$interval->days;

							}

							else{

								$td=$td+$interval->days+1;

							}



							while($row5=mysql_fetch_array($rs5)){

								if($row5['status']!='Reservation'){

									$time_out = $row5['time_out'];

									$rate = $row5['rate'];

									$time_in = $row5['time_in'];

									list($month,$day,$year) = explode('-',substr($time_out,0,10));

									list($date,$time) = explode(' ',$time_out);

									$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

									$date2 = new DateTime(date("Y-m-d H:i"));

									if(strlen(trim($time_in))!=0){

										list($month,$day,$year) = explode('-',substr($time_in,0,10));

										list($date,$time) = explode(' ',$time_in);

										$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

									}

									$interval = $date1->diff($date2);

									if($interval->h==0){

										$td=$td+$interval->days;

									}

									else{

										$td=$td+$interval->days+1;

									}

								}

							}



							/*if($interval->h==0){

								$in=$interval->days;

								$color='#000000';

								if($in>14){

									$color='red';

								}

								echo '<a style="color:'.$color.'">'.$interval->days.'</a>';

							}

							else{*/

								$in=$td;

								$color='#000000';

								if($in>14){

									$color='red';

								}

								$extr='';

								if($row5['status']==='Rented Out'){

									$extr=' (Out)';

									echo '<a style="color:'.$color.'; font-weight:bold">'.($td).$extr.'</a>';

								}

								else{

									echo '<a style="color:'.$color.'">'.($td).$extr.'</a>';

								}



							//}

						}

						else{

							echo 'Res';

						}

					}

					else{

						//No Rental from Fleet

						echo 'N/A';

					}

				?>

                </td>

                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col4;?>"><?php

					$sql3="SELECT * FROM adjuster WHERE id='".$row['adjuster_id']."'";

					$rs3=mysql_query($sql3);

					$row3=mysql_fetch_array($rs3);

					echo $row3['name'];

			?></td>

            <?php

				$ex = '';

				if($bg!=0){

					$ex='style="';

				}

				if($row['status']==='Reservation'){

					$ex=$ex.'color:blue;';

				}

				if($row['status']==='Pending Payment'){

					$ex=$ex.'color:red;';

				}

				if($row['status']==='Open'){

					$ex=$ex.'color:green;';

				}



				$ex=$ex.'"';







			?>

            <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC;';} echo $ex;?> >

              <?php



					$color='#000000';

					if($day_diff<=5){

						$color='#348017';

					}

					else if($day_diff<=8){

						$color='#FFA500';

					}

					else{

						$color='red';

					}

					echo '<a style="color:'.$color.'">'.$day_diff.'</a>';



			  ?>

            </td>

            <td class="middle-right-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC;';} echo $ex;?> width="<?php echo $col4;?>">

                <?php

					$sql3="SELECT * FROM status_survey WHERE id='".$row['status_id']."'";

					$rs3=mysql_query($sql3);

					$row3=mysql_fetch_array($rs3);

					$color='#000000';

					if($row['status_id']==1){

						$color='red';

					}

					else if($row['status_id']==2){

						// In Progress

						if(trim($row['est_report_t'])!==''){

							$color='green';

						}

						else if(trim($row['neg_review_t'])!==''){

							$color='#7E0084';

						}

						else if(trim($row['parts_prices_t'])!==''){

							$color='#09018B';

						}

						else if(trim($row['quotation_t'])!==''){

							$color='#0058FE';

						}

						else if(trim($row['parts_list_t'])!==''){

							$color='#65FFA9';

						}

						else if(trim($row['survey_vehicle_t'])!==''){

							$color='#FF8E02';

						}

						else if(trim($row['calculation_t'])!==''){

							$color='#FF1E02';

						}

						else{

							$color='#5C0100';



						}





					}

					else if($row['status_id']==3){

						$color='#0000A0';

					}



					echo '<a style="color:'.$color.'; font-weight:bold">'.$row3['status'];

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

				} // End Rep Damage

			}

		?>

        <tr><td class="middle-child" colspan="13" align="right">&nbsp;</td></tr>

        <tr>

        	<td class="bottom-child" colspan="13">Page

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

							echo '<a style="color:#DCB272" href="/survey_list.php?page='.$start_page.'&edate='.$edate.'&sdate='.$sdate.'&adjuster='.$adjuster.'&status='.$status.'&rep_damage='.$_REQUEST['rep_damage'].'&survey_duration='.$_REQUEST['survey_duration'].'">'.$start_page.'</a> , <a style="color:#DCB272" href="/roadservice/survey_list.php?page='.($start_page+1).'"> More</a>';

						}

						else if($page == $start_page){

							echo '<<a style="color:#DCB272; font-weight: bold" href="/survey_list.php?page='.$start_page.'&edate='.$edate.'&sdate='.$sdate.'&adjuster='.$adjuster.'&status='.$status.'&rep_damage='.$_REQUEST['rep_damage'].'&survey_duration='.$_REQUEST['survey_duration'].'">'.$start_page.'</a>> , ';

						}

						else if ($less > 1){

							echo '<a style="color:#DCB272" href="/survey_list.php?page='.($less).'"> Less</a> , <a style="color:#DCB272" href="/roadservice/survey_list.php?page='.$start_page.'&edate='.$edate.'&sdate='.$sdate.'&adjuster='.$adjuster.'&status='.$status.'&rep_damage='.$_REQUEST['rep_damage'].'&survey_duration='.$_REQUEST['survey_duration'].'">'.$start_page.'</a> , ';

							$less = 0;

						}

						else{

							echo '<a style="color:#DCB272" href="/survey_list.php?page='.$start_page.'&edate='.$edate.'&sdate='.$sdate.'&adjuster='.$adjuster.'&status='.$status.'&rep_damage='.$_REQUEST['rep_damage'].'&survey_duration='.$_REQUEST['survey_duration'].'">'.$start_page.'</a> , ';

						}

						$start_page++;

					}

				?>

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

</script>

</body>

</html>
