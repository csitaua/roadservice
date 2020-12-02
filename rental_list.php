<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();
if(isPolice()){
	header('location:index.php');
}
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$page = $_REQUEST['page'];
if(!$page){
	$page = 1;
}

$paging = 30; //items per page
$page_dif = 5;
$col1 = 45;
$col2 = 132;
$col3 = 75;
$col4 = 50;

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

if($_REQUEST['aid']){
	$faid = $_REQUEST['aid'];
}
else{
	$faid = 0;
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

if($_REQUEST['mr']){
	$mr = $_REQUEST['mr'];
}
else{
	$mr = 0;
}

$sdate = $_REQUEST['sdate'];
$_POST['sdate']=$sdate;
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
	$sdate = '';
	$_POST['status1']='';
	$_POST['vehicle']='';
}

echo menu();

if($sid != 0 || $receipt != 0){
	if($receipt != 0){
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
	}
}

if(isset($_POST['clear'])){
 header('location:rental_list.php');
}

$filter = '';
if(trim($_POST['fid'])!==''){
	$filter = "AND `id`=".$_POST['fid']." ";
}
if(trim($_POST['fclaimNo'])!==''){
	$filter = "AND `claimNo` LIKE '".$_POST['fclaimNo']."' ";
}
if(trim($_POST['license'])!==''){
	$sql4="SELECT * FROM rental_vehicle WHERE licenseplate='".$_POST['license']."'";
	$rs4=mysql_query($sql4);
	$row4=mysql_fetch_array($rs4);
	$filter = "AND `rental_vehicle_id` = '".$row4['id']."' ";
}
if(isset($_POST['status1']) && $_POST['status1']!==''){
	$filter .= "AND `status`='".$_POST['status1']."'";
}
if(isset($_POST['vehicle']) && $_POST['vehicle']!==''){
	$filter .= "AND `rental_vehicle_id`='".$_POST['vehicle']."'";
}
if($_POST['sdate']  !=''){
	$filter .= " AND STR_TO_DATE(`time_out`,'%m-%d-%Y %H:%i') <= STR_TO_DATE('".$sdate." 23:59','%m-%d-%Y %H:%i') AND (`time_in` IS NULL OR STR_TO_DATE(`time_in`,'%m-%d-%Y %H:%i') >= STR_TO_DATE('".$sdate." 00:00','%m-%d-%Y %H:%i'))";
}
$status=$_POST['status1'];
$vehicle=$_POST['vehicle'];



?>

<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="11" align="center" style="border:0;color:#148540"><h8 style="color:#FFF">Rental List</h8></td>
        </tr>
         <tr><td colspan="11">&nbsp;</td></tr>
         <form name="filter" action="" method="post">
         <tr>
         	<td class="child" colspan="12">
            	<table width="100%">
                <tr>
                	<td width="10%">ID:</td>
                	<td width="10%"><input type="text" name="fid" style="background-color:#FAD090"  value="<?php echo $_POST['fid'];?>"/></td>
                    <td width="10%">License Plate:</td>
                	<td width="10%">
                    <select name="vehicle" style="background-color:#FAD090"/>
                    <option value="">All</option>
            	<?php
					$sql3 = "SELECT * FROM `rental_vehicle` WHERE `active`=1 order by make,model";
					$rs3 = mysql_query($sql3);
					while($row3 = mysql_fetch_array($rs3)){
						?>
                        	<option <?php if($row3['id']==$vehicle){echo 'selected="selected"';}?> value="<?php echo $row3['id'];?>"><?php echo $row3['make'].' '.$row3['model'].' ('.$row3['licenseplate'].')';?></option>
                        <?php
					}
				?>
            </select>
                    </td>
                    <td width="10%">Status:</td>
                	<td width="10%">
                    <select name="status1" style="background-color:#FAD090">
                    <option value="">All</option>
            	<option <?php if(strcmp($status,'Open')==0){echo 'selected="selected"';}?> value="Open">Open</option>
                <option <?php if(strcmp($status,'Reservation')==0){echo 'selected="selected"';}?> value="Reservation">Reservation</option>
                <option <?php if(strcmp($status,'Rented Out')==0){echo 'selected="selected"';}?> value="Rented Out">Rented Out</option>
                 <option <?php if(strcmp($status,'Pending Invoice')==0){echo 'selected="selected"';}?> value="Pending Invoice">Pending Invoice</option>
                <option <?php if(strcmp($status,'Pending Payment')==0){echo 'selected="selected"';}?> value="Pending Payment">Pending Payment</option>
                <option <?php if(strcmp($status,'Missing Information')==0){echo 'selected="selected"';}?> value="Missing Information">Missing Information</option>
               	<option <?php if(strcmp($status,'Cancelled')==0){echo 'selected="selected"';}?> value="Cancelled">Cancelled</option>
                <option <?php if(strcmp($status,'Closed')==0){echo 'selected="selected"';}?> value="Closed">Closed</option>
            </select>
                    </td>
            	 	<td colspan="5" align="right"><input type="submit" name="filter" value="Filter" style="width:85px"/></td>
        		 </tr>
                 <tr>
                 	<td width="10%">Claim No:</td>
                	<td width="10%"><input type="text" name="fclaimNo" style="background-color:#FAD090"  value="<?php echo $_POST['fclaimNo'];?>"/></td>
                    <td width="10%">Date:</td>
                    <td colspan="4"><input type="text" id="sdate" name="sdate" readonly/ size="13" value="<?php echo $sdate;?>"/>
              <button id="sdatebutton">
                <img src="anytime/calendar.png" alt="[calendar icon]"/>
              </button>
              <script>
                $('#sdatebutton').click(
                  function(e) {
                    $('#sdate').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();
                    e.preventDefault();
                  } );
              </script></td>
                 	<td colspan="3" align="right"><input type="submit" name="clear" value="Clear" style="width:85px"/></td>
                	</tr>
                </table>
            </td>
         </tr>

         </form>
        <tr><td colspan="11">&nbsp;</td></tr>
        <tr>
        	<td class="top-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2+15;?>">Date Out</td>
            <td colspan="2" class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2+$col3;?>">Car</td>
            <td colspan="2" class="top-center-child" style="background-color:#ECF65C;" width="<?php echo ($col4-15);?>">Requested By</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Driver License</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Expected Return Date</td>
                        <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Claims No.</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">Days</td>
			<td  class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Due</td>
            <td class="top-right-child" style="background-color:#ECF65C;" width="<?php echo $col4?>">Status</td>

        </tr>
        <?php
			if(1){

				$sql = "SELECT * FROM `rental` WHERE `active`=1 ".$filter." order by STR_TO_DATE( `time_out` , '%m-%d-%Y %k:%i' ) DESC, id DESC";
				$rs = mysql_query($sql);
				$num_rows = mysql_num_rows($rs);
				$end = ($paging * $page)-1;
				$current_pt = ($paging * $page) - $paging;
				$sql = "SELECT * FROM `rental` WHERE `active`=1 ".$filter." order by STR_TO_DATE( `time_out` , '%m-%d-%Y %k:%i' ) DESC, id DESC LIMIT ".$current_pt.", ".$paging;
				$rs = mysql_query($sql);
			}
			$bg = 0;
			while($row = mysql_fetch_array($rs)){

		?>
        	<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>
                <td class="middle-left-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="rental_detail.php?id=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>
                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2+10;?>"><?php echo substr($row['time_out'],0,16);?></td>
             	<td colspan="2" class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2+$col3;?>"><?php
					$rental = $row['rental_vehicle_id'];
					$sql2 = "SELECT * FROM `rental_vehicle` WHERE `id`='$rental'";
					$rs2 = mysql_query($sql2);
					$row2 = mysql_fetch_array($rs2);
					echo $row2['make'].' '.$row2['model'].' ('.$row2['licenseplate'].')';

				?></td>
                <td colspan="2" class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo ($col3-15+$col2);?>"><?php
				if(is_numeric($row['requested_by'])){
					$sql3 = "SELECT * FROM rental_request WHERE id = '".$row['requested_by']."'";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					echo $row3['name'];
				}
				else { echo $row['requested_by']; }
				?></td>
                	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>">
                <?php
					echo $row['extra_drv'];
				?>
              </td>
               	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>">
                <?php
				if($row['status']!='Reservation' && trim($row['time_in_exp'])!==''){
					list($month,$day,$year) = explode('-',substr($row['time_in_exp'],0,10));
					$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
					$date2 = new DateTime(date("Y-m-d H:i"));
					$interval = $date1->diff($date2);
					/*if($interval->h==0){
						echo $interval->days.' X '.number_format($rate,2).' = '.number_format($rate*$interval->days,2);
					}
					else{
						echo ($interval->days+1).' X '.number_format($rate,2).' = '.number_format($rate*($interval->days+1),2);
					}*/
					$ou=($interval->days*24)+$interval->h;
					if($ou < 12 && ($row['status']==='Open' || $row['status']==='Rented Out' || $row['status']==='Reservation')){
						echo '<span style="color:red; font-weight:bold">'.$row['time_in_exp'].'</span>';
					}
					else if($ou < 48 && ($row['status']==='Open' || $row['status']==='Rented Out' || $row['status']==='Reservation')){
						echo '<span style="color:orange; font-weight:bold">'.$row['time_in_exp'].'</span>';
					}
					else{
						echo $row['time_in_exp'];
					}
				}
				?>
              </td>
              <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2;?>">
                <?php echo $row['claimNo'];?>
              </td>
              	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col1;?>">
				 <?php
				if($row['status']!='Reservation'){
				 $time_out = $row['time_out'];
				$rate = $row['rate'];
				$time_in = $row['time_in'];
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
				if($interval->h==0 && $row['status']!='Cancelled'){
					echo $interval->days;
				}
				else if($row['status']!='Cancelled'){
					echo ($interval->days+1);
				}
				}
			?>
                </td>
                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col4;?>"><?php
				if($row['status']!='Reservation'){
				$time_out = $row['time_out'];
				$rate = $row['rate'];
				$time_in = $row['time_in'];
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
				if($interval->h==0 && $row['status']!='Cancelled'){
					echo number_format(($rate*$interval->days)-(($rate*$row['discount']/100)*($interval->days)),2);
				}
				else if ($row['status']!='Cancelled'){
					echo number_format(($rate*($interval->days+1))-(($rate*$row['discount']/100)*($interval->days+1)),2);
				}
				else {
					echo number_format(0,2);
				}
				}
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
            <td class="middle-right-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC;';} echo $ex;?> width="<?php echo $col4;?>">
                <?php echo $row['status'];?>
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
        <tr><td class="middle-child" colspan="12" align="right">&nbsp;</td></tr>
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
							echo '<a style="color:#DCB272" href="/rental_list.php?page='.$start_page.'&sdate='.$sdate.'">'.$start_page.'</a> , <a style="color:#DCB272" href="/roadservice/rental_list.php?page='.($start_page+1).'&sdate='.$sdate.'"> More</a>';
						}
						else if($page == $start_page){
							echo '<<a style="color:#DCB272; font-weight: bold" href="/rental_list.php?page='.$start_page.'&sdate='.$sdate.'">'.$start_page.'</a>> , ';
						}
						else if ($less > 1){
							echo '<a style="color:#DCB272" href="/rental_list.php?page='.($less).'"> Less</a> , <a style="color:#DCB272" href="/roadservice/rental_list.php?page='.$start_page.'&sdate='.$sdate.'">'.$start_page.'</a> , ';
							$less = 0;
						}
						else{
							echo '<a style="color:#DCB272" href="/rental_list.php?page='.$start_page.'&sdate='.$sdate.'">'.$start_page.'</a> , ';
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
