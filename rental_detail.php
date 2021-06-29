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

$col1 = 100;

$col2 = 225;

$vcol1 = 45;

$vcol2 = 55;



$idm=$_REQUEST['id'];





if(isset($_POST['submit'])){

	$money_delivered = 0;

	$paymentType = '';

	if (isset($_POST['money'])){

		$money_delivered = $_SESSION['user_id'];

		$paymentType = $_POST['paymentType'];

	}

	$data='';

	if(trim($_POST['extra_name'])!==''){

		$data=$data.", `extra_name`='".mysql_real_escape_string($_POST['extra_name'])."'";

	}

	if(trim($_POST['extra_drv'])!==''){

		$data=$data.", `extra_drv`='".mysql_real_escape_string($_POST['extra_drv'])."'";

	}

	if(trim($_POST['odo_in'])!==''){

		$data=$data.", `odo_in`=".mysql_real_escape_string($_POST['odo_in']);

	}

	if(trim($_POST['fuel_in'])!==''){

		$data=$data.", `fuel_in`='".mysql_real_escape_string($_POST['fuel_in'])."'";

	}

	if(trim($_POST['time_in'])!==''){

		$data=$data.", `time_in`='".mysql_real_escape_string($_POST['time_in'])."'";

	}

	if(trim($_POST['time_in_exp'])!==''){

		$data=$data.", `time_in_exp`='".mysql_real_escape_string($_POST['time_in_exp'])."'";

	}

	if(trim($_POST['time_out'])!==''){

		$data=$data.", `time_out`='".mysql_real_escape_string($_POST['time_out'])."'";

	}

	if(trim($_POST['status'])!==''){

		$data=$data.", `status`='".mysql_real_escape_string($_POST['status'])."'";

	}

	if(trim($_POST['odo_out'])!==''){

		$data=$data.", `odo_out`=".mysql_real_escape_string($_POST['odo_out']);

	}

	if(trim($_POST['fuel_out'])!==''){

		$data=$data.", `fuel_out`='".mysql_real_escape_string($_POST['fuel_out'])."'";

	}

	if(trim($_POST['claimNo'])!==''){

		$data=$data.", `claimNo`='".mysql_real_escape_string($_POST['claimNo'])."'";

	}

	if(trim($_POST['issued_location'])!==''){

		$data=$data.", `issued_location`='".mysql_real_escape_string($_POST['issued_location'])."'";

	}

	if(trim($_POST['returned_location'])!==''){

		$data=$data.", `returned_location`='".mysql_real_escape_string($_POST['returned_location'])."'";

	}

	$data = $data.", `attendee_in`='".mysql_real_escape_string($_POST['attendee_in'])."'";

	$data = $data.(trim($_POST['out_id'])!== '' ? ", out_id='".$_POST['out_id']."'" : "");

	$data = $data.(trim($_POST['in_id'])!== '' ? ", in_id='".$_POST['in_id']."'" : "");

	$data = $data.(trim($_POST['out_st'])!== '' ? ", out_st='".$_POST['out_st']."'" : "");

	$data = $data.(trim($_POST['in_st'])!== '' ? ", in_st='".$_POST['in_st']."'" : "");

	$data = $data.(trim($_POST['out_lp'])!== '' ? ", out_lp='".$_POST['out_lp']."'" : "");

	$data = $data.(trim($_POST['in_lp'])!== '' ? ", in_lp='".$_POST['in_lp']."'" : "");

	$data = $data.(trim($_POST['out_ct'])!== '' ? ", out_ct='".$_POST['out_ct']."'" : "");

	$data = $data.(trim($_POST['in_ct'])!== '' ? ", in_ct='".$_POST['in_ct']."'" : "");

	$data = $data.(trim($_POST['out_r'])!== '' ? ", out_r='".$_POST['out_r']."'" : "");

	$data = $data.(trim($_POST['in_r'])!== '' ? ", in_r='".$_POST['in_r']."'" : "");

	$data = $data.(trim($_POST['out_cd'])!== '' ? ", out_cd='".$_POST['out_cd']."'" : "");

	$data = $data.(trim($_POST['in_cd'])!== '' ? ", in_cd='".$_POST['in_cd']."'" : "");

	$data = $data.(trim($_POST['notes'])!== '' ? ", notes ='".mysql_real_escape_string($_POST['notes'])."'" : "");

	$data = $data.(trim($_POST['discount'])!== '' ? ", discount ='".mysql_real_escape_string($_POST['discount'])."'" : "");

	$data = $data.(trim($paymentType)!== '' ? ", paymentType='".$paymentType."'" : "");





	$sql = "UPDATE `rental` SET `requested_by`='".mysql_real_escape_string($_POST['requestedBy'])."', `rental_company_id`='".$_POST['company']."' ".$data.", bill_to ='".mysql_real_escape_string($_POST['bill_to'])."', money_delivered='$money_delivered', attendee='".$_POST['attendee']."'  WHERE `id`='$idm'";

	mysql_query($sql);

	//echo mysql_error().'<br/>'.$sql;



	if(strcmp($_POST['status'],'Closed')==0 || strcmp($_POST['status'],'Pending Payment')==0){

		$sql = "SELECT * FROM `rental` WHERE `id`='$idm'";

		$rs = mysql_query($sql);

		$row = mysql_fetch_array($rs);

		$rental_veh = $row['rental_vehicle_id'];

		$sql = "UPDATE `rental_vehicle` SET `available`=1, `currentRentalId`=0 WHERE `id`='$rental_veh' AND `currentRentalId`='$idm'";

		mysql_query($sql);

	}



	$sql = "SELECT * FROM `rental` WHERE `id`='$idm'";

	$rs = mysql_query($sql);

	$row = mysql_fetch_array($rs);

	if($row['rental_vehicle_id'] != $_POST['vehicle']){ //vehicle change

		$rental_veh = $row['rental_vehicle_id'];

		$sql = "UPDATE `rental_vehicle` SET `available`=1, `currentRentalId`=0 WHERE `id`='$rental_veh'";

		mysql_query($sql);



		$sql = "SELECT * FROM rental_vehicle WHERE `id`='".$_POST['vehicle']."'";

		$rs = mysql_query($sql);

		$row = mysql_fetch_array($rs);

		$rate = $row['rental'];



		$sql = "UPDATE `rental` SET `rental_vehicle_id`='".$_POST['vehicle']."', `rate`='$rate' WHERE `id`='$idm'";

		mysql_query($sql);



		$sql = "UPDATE `rental_vehicle` SET `currentRentalId`='$idm' WHERE `id` = '".$_POST['vehicle']."'";

		mysql_query($sql);

	}



	//save file only pdf

	$ext = end(explode('.', $_FILES["image_upload_box"]["name"]));

	$id=$idm;

	if(strcmp($ext,'pdf')==0){

		mkdir(FOLDER.'/rental/inspection/'.$id);

		$path = FOLDER.'/rental/inspection/'.$id;



		$remote_file = $path.'/inspection-'.$id.'.'.$ext;





		move_uploaded_file($_FILES['image_upload_box']['tmp_name'], $remote_file);

	}



}



$sql = "SELECT * FROM `rental` WHERE `id`='$_REQUEST[id]'";

$rs = mysql_query($sql);

$row = mysql_fetch_array($rs);

$vehicle = $row['rental_vehicle_id'];

$requestedBy = $row['requested_by'];

$requestedByid = 0;

$odo_out = $row['odo_out'];

$extra_name = $row['extra_name'];

$fuel_out = $row['fuel_out'];

$extra_drv = $row['extra_drv'];

$time_out = $row['time_out'];

$rate = $row['rate'];

$time_in = '';

$status = $row['status'];

$odo_in = $row['odo_in'];

$fuel_in = $row['fuel_in'];

$time_in = $row['time_in'];

$policy = $row['policy_no'];

$claimNo = $row['claimNo'];

$service_req_id = $row['service_req_id'];

$issued_location = $row['issued_location'];

$returned_location = $row['returned_location'];

$attendee = $row['attendee'];

$attendee_in = $row['attendee_in'];

$time_in_exp=$row['time_in_exp'];

$rental_company_id=$row['rental_company_id'];

$enter_by_id=$row['enter_by_id'];



if(is_numeric($requestedBy)){

	$sql2 = "SELECT * FROM rental_request WHERE id='$requestedBy'";

	$rs2 = mysql_query($sql2);

	$row2 = mysql_fetch_array($rs2);

	$requestedByid = $requestedBy;

	$requestedBy = $row2['name'];



}



if($odo_in==0){

	$odo_in='';

}



echo menu();



?>



<form name="new_rental"  enctype="multipart/form-data" action="" method="post">

	<table width="1000" cellspacing="0">

    	<tr>

        	<td colspan="5" align="center" style="border:0;color:#148540"><div class="rounded_h">

        	  <h3>Rental Detail (<?php echo $status;?>) <?php echo $idm;?></h3></div></td>

        </tr>

        <tr>

        <tr><td colspan="5">&nbsp;</td></tr>

        <tr>

        	<td colspan="5">&nbsp;<input type="hidden" name="idm" value="<?php echo $idm;?>"/>

        <input type="hidden" name="acc_link" value="<?php echo $acc_link;?>"/>

        	</td>

        </tr>

        <tr>

        	<td class="top-left-child" width="<?php echo $col1;?>">Company:</td>

            <td class="top-center-child" width="<?php echo $col2;?>">

            <select name="company" style="background-color:#FAD090">



            	<?php

					if($rental_company_id==0){

						echo '<option value="" selected="selected"></option>';

					}

					$sql2 = "SELECT * FROM `rental_company`";

					$rs2 = mysql_query($sql2);

					while($row2 = mysql_fetch_array($rs2)){

						?>

                        	<option <?php if($row2['id']==$rental_company_id){echo 'selected="selected"';}?> value="<?php echo $row2['id'];?>"><?php echo $row2['name'];?></option>

                        <?php

					}

				?>

            </select>

            </td>

            <td colspan="2" class="top-right-child" style="font-weight:bold">&nbsp;</td>

        </tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Car:</td>

            <td class="middle-none-child" width="<?php echo $col2;?>">

            <select name="vehicle" style="background-color:#FAD090">

            	<?php

					$sql2 = "SELECT * FROM `rental_vehicle` WHERE `id`='$vehicle' or `active`=1 ";

					$rs2 = mysql_query($sql2);

					while($row2 = mysql_fetch_array($rs2)){

						?>

                        	<option <?php if($row2['id']==$vehicle){echo 'selected="selected"';}?> value="<?php echo $row2['id'];?>"><?php echo $row2['make'].' '.$row2['model'].' ('.$row2['licenseplate'].')';?></option>

                        <?php

					}

				?>

            </select>

            </td>

            <td colspan="2" class="middle-right-child">&nbsp;</td>

      	</tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>

            <td class="middle-none-child" width="<?php echo $col2;?>">

           	<select style="background-color:#FAD090" name="requestedBy">

            	<?php

					if($requestedByid == 0){

						echo '<option selected="selected" value="'.$requestedBy.'">'.$requestedBy.'</option>';

					}

					$sql2 = "SELECT * FROM rental_request WHERE active=1 order by name";

					$rs2 = mysql_query($sql2);

					while($row2=mysql_fetch_array($rs2)){

						if($row2['id']==$requestedByid){

							echo '<option selected="selected" value="'.$row2['id'].'">'.$row2['name'].'</option>';

						}

						else{

							echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';

						}

					}

				?>

            </select>

            </td>

            <td colspan="2" class="middle-right-child">&nbsp;</td>

      	</tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Attendee Out:</td>

            <td class="middle-none-child" width="<?php echo $col2;?>">

           	<select style="background-color:#FAD090" name="attendee">

            	<?php

					$sql2 = "SELECT * FROM rental_attendee WHERE active=1 order by name";

					$rs2 = mysql_query($sql2);

					while($row2=mysql_fetch_array($rs2)){

						if($row2['id']==$attendee){

							echo '<option selected="selected" value="'.$row2['id'].'">'.$row2['name'].'</option>';

						}

						else{

							echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';

						}

					}

				?>

            </select>

            </td>

            <td colspan="2" class="middle-right-child">&nbsp;</td>

      	</tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Issued Location:</td>

            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="issued_location" size="25" maxlength="50" value="<?php echo $issued_location; ?>"/></td>

        	<td colspan="2" class="middle-right-child" style="font-weight:bold">&nbsp;</td>

      	</tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Odometer Out:</td>

            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" name="odo_out" size="15" value="<?php echo $odo_out;?>" style="background-color:#FAD090"/></td>

            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>

            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>

        </tr>

        <tr >

        	<td class="middle-left-child" width="<?php echo $col1;?>">Fuel Out%:</td>

            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" name="fuel_out" size="15" value="<?php echo $fuel_out;?>" style="background-color:#FAD090" /></td>

            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>

            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>" valign="top">Date Time Out:</td>

        	<td class="middle-none-child" width="<?php echo $col2;?>" valign="top">

            	<input type="text" id="time_out" name="time_out" readonly value="<?php echo $time_out;?>" />

  <button id="timeoutbutton">

    <img src="anytime/calendar.png" alt="[calendar icon]"/>

  </button>

  <script>

    $('#timeoutbutton').click(

      function(e) {

        $('#time_out').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();

        e.preventDefault();

      } );

  </script>

            </td>

            <td colspan="2" class="middle-right-child">&nbsp;</td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>" valign="top">Number Days Out / Due:</td>

        	<td class="middle-none-child" width="<?php echo $col2;?>" valign="top">

            <?php

				if($status!=='Reservation'){

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

						echo $interval->days.' X '.number_format($rate,2).' = '.number_format($rate*$interval->days,2);

					}

					else{

						echo ($interval->days+1).' X '.number_format($rate,2).' = '.number_format($rate*($interval->days+1),2);

					}

				}

			?>

            </td>

            <td colspan="2" class="middle-right-child">&nbsp;</td>

       	</tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>" valign="top">Attendee In:</td>

        	<td class="middle-none-child" width="<?php echo $col2;?>" valign="top">

            <select style="background-color:#FAD090" name="attendee_in">

            	<?php

					$sql2 = "SELECT * FROM rental_attendee WHERE active=1 order by name";

					$rs2 = mysql_query($sql2);

					$i=0;

					while($row2=mysql_fetch_array($rs2)){

						if($attendee_in==0 && $i==0){

							echo '<option selected="selected" value="0">Pending</option>';

							$i=1;

						}

						if($row2['id']==$attendee_in){

							echo '<option selected="selected" value="'.$row2['id'].'">'.$row2['name'].'</option>';

						}

						else{

							echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';

						}

					}

				?>

            </select>

            </td>

            <td colspan="2" class="middle-right-child">&nbsp;</td>

       	</tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>" valign="top">Returned Location:</td>

        	<td class="middle-none-child" width="<?php echo $col2;?>" valign="top"><input type="text" name="returned_location" style="background-color:#FAD090"  size="25" maxlength="50" value="<?php echo $returned_location;?>"/></td>

            <td colspan="2" class="middle-right-child">&nbsp;</td>

       	</tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>" valign="top">Odometer In:</td>

        	<td class="middle-none-child" width="<?php echo $col2;?>" valign="top"><input type="text" name="odo_in" <?php echo 'style="background-color:#FAD090"';?>  size="15" value="<?php echo $odo_in;?>"/></td>

             <td class="top-left-child" width="<?php echo $col1;?>">Policy Number:</td>

            <td class="top-right-child" width="<?php echo $col2;?>"><input type="text" name="pol" size="25" value="<?php echo $policy;?>" readonly/>&nbsp;



        	</td>

       	</tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>" valign="top">Fuel In%:</td>

        	<td class="middle-none-child" width="<?php echo $col2;?>" valign="top"><input type="text" name="fuel_in" <?php echo 'style="background-color:#FAD090"';?> size="15" value="<?php echo $fuel_in;?>"/></td>

            <td class="middle-left-child" width="<?php echo $col1;?>">License Plate:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" name="licensePlate" size="25" value="<?php

			$rs2 = mysql_query("SELECT * FROM service_req WHERE id = '$service_req_id'");

			$row2= mysql_fetch_array($rs2); echo $row2['a_number'];  ?>" readonly/>&nbsp;



        	</td>

       	</tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>" valign="top">Exp. Time In:</td>

        	<td class="middle-none-child" width="<?php echo $col2;?>" valign="top">

            <input type="text" id="time_in_exp" name="time_in_exp" readonly value="<?php echo $time_in_exp;?>" />

                  <button id="timeinexpbutton">

                    <img src="anytime/calendar.png" alt="[calendar icon]"/>

                  </button>

                  <script>

                    $('#timeinexpbutton').click(

                      function(e) {

                        $('#time_in_exp').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();

                        e.preventDefault();

                      } );

                  </script>

            </td>

            <td class="middle-left-child" width="<?php echo $col1;?>">Claims Number:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" name="claimNo" size="25" value="<?php echo $claimNo;?>" maxlength="15" style="background-color:#FAD090"/>

        	</td>

       	</tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Date Time In:</td>

        	<td class="middle-none-child" width="<?php echo $col2;?>">

            	<input type="text" id="time_in" name="time_in" readonly value="<?php echo $time_in;?>" />

                  <button id="timeinbutton">

                    <img src="anytime/calendar.png" alt="[calendar icon]"/>

                  </button>

                  <script>

                    $('#timeinbutton').click(

                      function(e) {

                        $('#time_in').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();

                        e.preventDefault();

                      } );

                  </script>

            </td>

            <td class="middle-left-child" width="<?php echo $col1;?>">Service ID:</td>

            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" name="service_req_id" size="15" value="<?php echo $service_req_id;?>" maxlength="15" readonly/>&nbsp; <span>

            <?php if($service_req_id){ ?>

            	<div class="buttonwrapper">

					<a class="squarebutton" href="edit_sc.php?sc=<?php echo $service_req_id?>"><span>Service Request</span></a>

				</div>

            </span>

            <?php } ?></td>

       	</tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>" valign="top">Status:</td>

        	<td class="middle-none-child" width="<?php echo $col2;?>" valign="top">

            <?php if(strcmp($status,'Close')!=0){ ?>

            <select name="status" style="background-color:#FAD090">

            	<option <?php if(strcmp($status,'Open')==0){echo 'selected="selected"';}?> value="Open">Open</option>

                <option <?php if(strcmp($status,'Reservation')==0){echo 'selected="selected"';}?> value="Reservation">Reservation</option>

                <option <?php if(strcmp($status,'Rented Out')==0){echo 'selected="selected"';}?> value="Rented Out">Rented Out</option>

                 <option <?php if(strcmp($status,'Pending Invoice')==0){echo 'selected="selected"';}?> value="Pending Invoice">Pending Invoice</option>

                <option <?php if(strcmp($status,'Pending Payment')==0){echo 'selected="selected"';}?> value="Pending Payment">Pending Payment</option>

                <option <?php if(strcmp($status,'Missing Information')==0){echo 'selected="selected"';}?> value="Missing Information">Missing Information</option>

               	<option <?php if(strcmp($status,'Cancelled')==0){echo 'selected="selected"';}?> value="Cancelled">Cancelled</option>

                <option <?php if(strcmp($status,'Closed')==0){echo 'selected="selected"';}?> value="Closed">Closed</option>

            </select>

            <?php }

			else {

			?>

           	<input type="hidden" name="status" value="<?php echo $status;?>"/>

            <input type="text" name="status_temp" value="<?php echo $status;?>" size="15"/>

            <?php } ?>

            </td>

             <td class="bottom-left-child" width="<?php echo $col1;?>">Entered By</td>

            <td class="bottom-right-child" width="<?php echo $col2;?>"><?php

				if($enter_by_id!=0){

					echo getUserFNameID($enter_by_id);

				}

			?>

			</td>

       	</tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">Inspection (PDF):</td>

         	<td class="middle-right-child" colspan="3"><input name="image_upload_box" type="file" id="image_upload_box" size="40" style="background-color:#FAD090" /> * If there is in Inspection Form already it will override current on!</td>

      	</tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>

         	<td class="middle-right-child" colspan="3">

            <span>

            	<div class="buttonwrapper">

					<a class="squarebutton" href="rental_form.php?id=<?php echo $idm?>"><span>Download Rental Form</span></a>

				</div>



            </span>



            </td>

         </tr>

         <?php if(is_dir(FOLDER.'/rental/inspection/'.$idm)){ ?>

          <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>

         	<td class="middle-right-child" colspan="3">

            <span>

            	<div class="buttonwrapper">

					<a class="squarebutton" href="download.php?file=<?php echo FOLDER; ?>/rental/inspection/<?php echo $idm;?>/inspection-<?php echo $idm?>.pdf"><span>Download Inspection</span></a>

				</div>



            </span>



            </td>

         </tr>

       	<?php } ?>

          <?php

				$path=FOLDER.'/rentalpo/'.$idm.'/';

				if(!file_exists($path.$idm.'_Rental_PO.pdf')){

					echo '

				<tr>

        	<td class="middle-left-child" width="'.$col1.'">&nbsp;</td>

         	<td class="middle-right-child" colspan="3">

        	<span>

            	<div class="buttonwrapper">

					<a class="squarebutton" href="rental_po.php?id='.$idm.'"><span>Generate and Email PO</span></a>

				</div>

            </span>

          	</td>

        </tr>';

				}

		?>

        <?php

				$path=FOLDER.'/rentalpo/'.$idm.'/';

				if(file_exists($path.$idm.'_Rental_PO.pdf')){

					echo '

				<tr>

        	<td class="middle-left-child" width="'.$col1.'">&nbsp;</td>

         	<td class="middle-right-child" colspan="3">

				<span>

            	<div class="buttonwrapper">

					<a class="squarebutton" href="download.php?file='.$path.$idm.'_Rental_PO.pdf'.'"><span>Download PO</span></a>

				</div>

            	</span>

			</td>

			</tr>

					';

				}



			?>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>

         	<td class="middle-right-child" colspan="3">

        	<span>

            	<div class="buttonwrapper">

					<a class="squarebutton" href="rental_invoice.php?id=<?php echo $idm?>"><span>Download Invoice</span></a>

				</div>

            </span>

          	</td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>

         	<td class="middle-right-child" colspan="3">

            <?php

				if($_SESSION['user_level']==5){

					echo '

					<span>

            	<div class="buttonwrapper">

					<a class="squarebutton" href="delete_rental.php?id='.$idm.'"><span>Delete Rental</span></a>

				</div>

            </span>

					';

				}

				else{

					echo '&nbsp;';

				}

			?>



          	</td>

        </tr>

        <tr>

        	<td class="bottom-child" colspan="4" align="right">&nbsp;</td>

        </tr>

    </table>

    &nbsp;

    <table width="100%">

    	<tr>

        	<td width="50%" valign="top">

            	<table width="100%" cellspacing="0">

                	<tr>

                    	<td class="top-child_h" align="center" style="color:#148540" colspan="5"><h3>Vehicle Status</h3></td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" colspan="2" align="center">Out</td>

                    	<td class="middle-none-child">&nbsp;</td>

                        <td class="middle-right-child" colspan="2" align="center">In</td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" align="center" width="15%">Yes</td>

                    	<td class="middle-none-child" align="center" width="15%">No</td>

                    	<td class="middle-none-child" width="40%">&nbsp;</td>

                        <td class="middle-none-child" align="center" width="15%">Yes</td>

                    	<td class="middle-right-child" align="center" width="15%">No</td>

                    </tr>

                   	<tr>

                    	<td class="middle-left-child" align="center" width="15%"><input type="radio" name="out_id" value="1" <?php if($row['out_id']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" align="center" width="15%"><input type="radio" name="out_id" value="-1" <?php if($row['out_id']==-1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" width="40%" align="center">Insurance Docs</td>

                        <td class="middle-none-child" align="center" width="15%"><input type="radio" name="in_id" value="1" <?php if($row['in_id']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-right-child" align="center" width="15%"><input type="radio" name="in_id" value="-1" <?php if($row['in_id']==-1) echo 'checked="checked"';?>></td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" align="center" width="15%"><input type="radio" name="out_st" value="1" <?php if($row['out_st']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" align="center" width="15%"><input type="radio" name="out_st" value="-1" <?php if($row['out_st']==-1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" width="40%" align="center">Spare Tire</td>

                        <td class="middle-none-child" align="center" width="15%"><input type="radio" name="in_st" value="1" <?php if($row['in_st']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-right-child" align="center" width="15%"><input type="radio" name="in_st" value="-1" <?php if($row['in_st']==-1) echo 'checked="checked"';?>></td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" align="center" width="15%"><input type="radio" name="out_lp" value="1" <?php if($row['out_lp']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" align="center" width="15%"><input type="radio" name="out_lp" value="-1" <?php if($row['out_lp']==-1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" width="40%" align="center">License Plate</td>

                        <td class="middle-none-child" align="center" width="15%"><input type="radio" name="in_lp" value="1" <?php if($row['in_lp']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-right-child" align="center" width="15%"><input type="radio" name="in_lp" value="-1" <?php if($row['in_lp']==-1) echo 'checked="checked"';?>></td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" align="center" width="15%"><input type="radio" name="out_ct" value="1" <?php if($row['out_ct']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" align="center" width="15%"><input type="radio" name="out_ct" value="-1" <?php if($row['out_ct']==-1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" width="40%" align="center">Car Tools</td>

                        <td class="middle-none-child" align="center" width="15%"><input type="radio" name="in_ct" value="1" <?php if($row['in_ct']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-right-child" align="center" width="15%"><input type="radio" name="in_ct" value="-1" <?php if($row['in_ct']==-1) echo 'checked="checked"';?>></td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" align="center" width="15%"><input type="radio" name="out_r" value="1" <?php if($row['out_r']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" align="center" width="15%"><input type="radio" name="out_r" value="-1" <?php if($row['out_r']==-1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" width="40%" align="center">Radio</td>

                        <td class="middle-none-child" align="center" width="15%"><input type="radio" name="in_r" value="1" <?php if($row['in_r']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-right-child" align="center" width="15%"><input type="radio" name="in_r" value="-1" <?php if($row['in_r']==-1) echo 'checked="checked"';?>></td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" align="center" width="15%"><input type="radio" name="out_cd" value="1" <?php if($row['out_cd']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" align="center" width="15%"><input type="radio" name="out_cd" value="-1" <?php if($row['out_cd']==-1) echo 'checked="checked"';?>></td>

                    	<td class="middle-none-child" width="40%" align="center">Car Damage</td>

                        <td class="middle-none-child" align="center" width="15%"><input type="radio" name="in_cd" value="1" <?php if($row['in_cd']==1) echo 'checked="checked"';?>></td>

                    	<td class="middle-right-child" align="center" width="15%"><input type="radio" name="in_cd" value="-1" <?php if($row['in_cd']==-1) echo 'checked="checked"';?>></td>

                    </tr>

                    <tr><td colspan="5" class="bottom-child">&nbsp;</td></tr>

                </table>

                <br/>

                <table width="100%" cellspacing="0">

                	<tr>

                    	<td class="top-child_h" align="center" style="color:#148540" colspan="2"><h3>Adm / Payment Info</h3></td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" width="30%">Bill To:</td>

                        <td class="middle-right-child" ><select name="bill_to" style="background-color:#FAD090">

                        	<option value="1" <?php if($row['bill_to']==1) echo 'selected="selected"';?>>Nagico Claims</option>

                            <option value="2" <?php if($row['bill_to']==2) echo 'selected="selected"';?>>Driver</option>

                        </select></td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child">Discount %:</td>

                        <td class="middle-right-child" ><input <?php if($_SESSION['user_level'] < 5){echo 'readonly="readonly"';} ?> type="number" name="discount" size="5" maxlength="3" style="background-color:#FAD090" value="<?php echo $row['discount'];?>"/></td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" width="30%">Total Amount:</td>

                        <td class="middle-right-child" >

                        	<?php

                            if($interval->h==0){

							//	if($interval->days < 15 || $row['bill_to']==2){

					echo $interval->days.' X '.number_format($rate,2).' = '.number_format($rate*$interval->days,2).' - '.number_format(($rate*$row['discount']/100)*($interval->days),2).' (Discount)';

				//				}

				//				else{

				//					echo $interval->days.' X '.number_format($rate,2).' = '.number_format($rate*$interval->days,2).' - '.number_format(($rate-45)*($interval->days-14),2).' (Discount)';

				//				}

				}

				else{

					if($interval->days < 15 ||  $row['bill_to']==2){

					echo ($interval->days+1).' X '.number_format($rate,2).' = '.number_format($rate*($interval->days+1),2).' - '.number_format(($rate*$row['discount']/100)*($interval->days+1),2).' (Discount)';

					}

					else{

						echo ($interval->days+1).' X '.number_format($rate,2).' = '.number_format($rate*($interval->days+1),2).' - '.number_format(($rate-45)*(($interval->days+1)-14),2).' (Discount)';

					}

				}		?>

                        </td>

                    </tr>

                    <tr>

                    	<td class="middle-left-child" width="30%">Money Delivered:</td>

                        <td class="middle-right-child" >

                        	<?php

								$user_id = $_SESSION['user_id'];

								$sql3 = "SELECT * FROM rights WHERE `user_id`='$user_id' AND `department_id`=1";

								$rs3 = mysql_query($sql3);

								$row3 = mysql_fetch_array($rs3);

								if (mysql_num_rows($rs3) != 0 && $row3['right']==2){

							?>

								<input  type="checkbox" style="background-color:#FAD090" name="money" id="money" <?php if($row['money_delivered']>0){echo 'checked="checked"';}?> onchange="paymentTypeDisplay()"/>&nbsp;

								<select name="paymentType" id="paymentType" style="background-color:#FAD090; <?php if($row['money_delivered']==0){echo 'display:none';}  ?>" >

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

                    </tr>

                     <tr>

                    	<td class="middle-left-child" width="30%">Notes:</td>

                        <td class="middle-right-child" ><textarea  style="background-color:#FAD090" name="notes" rows="3" cols="30"><?php echo $row['notes'];?></textarea></td>

                    </tr>

                     <tr><td colspan="2" class="bottom-child"><input type="submit" name="submit" value="Submit"/></td></tr>

               	</table>



            </td>

            <td width="2%">&nbsp;</td>

            <td valign="top">

            	<table width="100%" cellspacing="0">

                	<tr>

                    	<td class="top-child_h" align="center" style="color:#148540" colspan="5"><h3>Drivers License Information</h3></td>

                    	<tr><td class="middle-child" colspan="5">&nbsp;</td></tr>

                        <?php

							$sql3 = "SELECT * FROM drivers_license where id = '$extra_drv'";

							$rs3 = mysql_query($sql3);

							$row3 = mysql_fetch_array($rs3);

						?>

                         <tr>

                            <td class="middle-left-child">Drivers License:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="text" name="extra_drv" id="extra_drv" maxlength="25" size="25" <?php if($row2['ph']!=0){echo 'disabled="disabled"';} echo 'value="'.$extra_drv.'"';?> class="fill" required="required" />

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">First Name:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="text" name="fname" id="fname" maxlength="50" size="35" <?php

                                echo 'value="'.$row3['firstName'].'"'; ?> readonly />

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Last Name:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="text" name="lname" id="lname" maxlength="50" size="35" <?php echo 'value="'.$row3['lastName'].'"';?> readonly />

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Email:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="email" name="email" id="email" maxlength="75" size="45" <?php  echo 'value="'.$row3['email'].'"';?> readonly />

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Address:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="text" name="address" id="address" maxlength="75" size="45" <?php  echo 'value="'.$row3['address'].'"';?> readonly/>

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Birth Day:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="text" id="bday" name="bday" readonly <?php  echo 'value="'.$row3['birthDay'].'"';?>/>

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Birth Place:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="text" name="bplace" id="bplace" maxlength="75" size="35" <?php  echo 'value="'.$row3['birthPlace'].'"';?> readonly />

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Gender:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="text" name="gender" id="gender" maxlength="75" size="20" <?php if(strcmp($row3['gender'],'m')==0){echo 'value="Male"';} else{echo 'value="Female"';} ?> readonly />

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Phone / Cell:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="tel" name="phone" id="phone" size="15" maxlength="15" <?php echo 'value="'.$row3['phone'].'"';?> readonly/>&nbsp;/&nbsp;<input type="tel" name="mobile" id="mobile" size="15" maxlength="15" <?php echo 'value="'.$row3['mobile'].'"';?> readonly/>

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Category:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="text" name="cat" id="cat" size="25" maxlength="25" <?php echo 'value="'.$row3['category'].'"';?> readonly/>

                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Expire Date:</td>

                            <td class="middle-right-child" colspan="4">

                                <input type="text" id="exp_date" name="exp_date" readonly <?php  echo 'value="'.$row3['expireDate'].'"';?>/>



                            </td>

                        </tr>

                        <tr>

                            <td class="middle-left-child">Drivers License:</td>

                            <td class="middle-right-child" colspan="4">

                                <?php



                                    $sql4 = "SELECT * FROM drivers_license where id = '$id'";

                                    $rs4 = mysql_query($sql4);

                                    if($row3){

                                 ?>

                                    <a target="_blank" href="download.php?file=<?php echo $row3['loc'];?>"><img width="200" src="download.php?file=<?php echo $row3['loc'];?>" /></a>

                                <?php

                                    }

                                    else if($row4 = mysql_fetch_array($rs4)){

                                 ?>

                                    <a target="_blank" href="download.php?file=<?php echo $row4['loc'];?>"><img width="200" src="download.php?file=<?php echo $row4['loc'];?>" /></a>

                                <?php

                                    }

                                ?>

                                     <div id="dr_upload" style="display:block" class="buttonwrapper"><a class="squarebutton" href="javascript:popacc('ins_drivers_license.php?license=<?php echo $row['extra_drv']?>&close=0');"><span>Insert/Edit Drivers License</span></a></div>

                            </td>

                     	</tr>

                      	<tr><td colspan="5" class="bottom-child">&nbsp;</td></tr>

             	</table>

            </td>

        </tr>

    </table>

</form>

<script  type="text/javascript">



	function showUpload(){

		if( document.getElementById('extra_drv').value.length != 0){

			document.getElementById('upload_drv').style.display = 'inline';

		}

		else{

			document.getElementById('upload_drv').style.display = 'none';

		}

	}



	function uploadLicense(){

		licensenumber = document.getElementById('extra_drv').value;

		window.open('ins_drivers_license.php?license='+licensenumber);

	}



	function paymentTypeDisplay(){

		if( document.getElementById('money').checked){

			document.getElementById('paymentType').style.display = 'inline';

		}

		else{

			document.getElementById('paymentType').style.display = 'none';

		}

	}



</script>

<script type="text/javascript" src="js/functions.js">



</script>

</body>

</html>
