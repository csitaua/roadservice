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
$filter='';

if(strcmp($_REQUEST['clear'],'Clear') == 0){ //Clear filter
	$_REQUEST['vehicle']='';
	$_REQUEST['status']='';
	$_REQUEST['carWashVendor']='';
	$_REQUEST['sdate'] = '';
	$_REQUEST['tdate'] = '';
}

$sdate = $_REQUEST['sdate'];
$tdate = $_REQUEST['tdate'];

if($_REQUEST['fid']){
	header('location:car_wash_detail.php?id='.$_REQUEST['fid']);
}
if($_REQUEST['vehicle']){
	$filter.= " AND `car_wash_vehicle_id`='".$_REQUEST['vehicle']."'";
}
if($_REQUEST['status']){
	$filter.= " AND `status`='".$_REQUEST['status']."'";
}
if($_REQUEST['carWashVendor']){
	$filter.= " AND `car_wash_vendor_id`='".$_REQUEST['carWashVendor']."'";
}
if($_REQUEST['fid']){
	$filter.= " AND `car_wash_vendor_id`='".$_REQUEST['carWashVendor']."'";
}
if(strcmp($sdate,'')!=0){
					$filter = $filter." AND STR_TO_DATE(`out_date_time`,'%Y-%m-%d') >= STR_TO_DATE('".$sdate." 00:00:00','%m-%d-%Y') ";
}
if(strcmp($tdate,'')!=0){
					$filter = $filter." AND STR_TO_DATE(`out_date_time`,'%Y-%m-%d') <= STR_TO_DATE('".$tdate." 00:00:00','%m-%d-%Y') ";
}




$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];
$anumber = $_REQUEST['anumber'];

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



?>

<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="11" align="center" style="border:0;color:#148540"><h8 style="color:#FFF">Car Wash List</h8></td>
        </tr>
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
                        <option value=""></option>
            	<?php
					$sql = "SELECT * FROM `car_wash_vehicle` WHERE `active`=1 ORDER BY make,model";
					$rs = mysql_query($sql);
					while($row = mysql_fetch_array($rs)){
						?>
                        	<option <?php if($row['id']==$_REQUEST['vehicle']){echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['make'].' '.$row['model'].' ('.$row['license_plate'].')';?></option>
                        <?php
					}
				?>
            </select>
                    </td>
                    <td width="20%" colspan="2">Date From:

                    	<input type="text" id="sdate" name="sdate" readonly/ size="13" value="<?php echo $sdate;?>"/>
              <button id="sdatebutton">
                <img src="anytime/calendar.png" alt="[calendar icon]"/>
              </button>
              <script>
                $('#sdatebutton').click(
                  function(e) {
                    $('#sdate').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();
                    e.preventDefault();
                  } );
              </script>
                    </td>
            	 	<td colspan="5" align="right"><input type="submit" name="filter" value="Filter" style="width:85px"/></td>
        		 </tr>
                 <tr>
                 	<td width="10%">Car Wash Company:</td>
                	<td width="10%">
                    	<select style="background-color:#FAD090" name="carWashVendor">
                        <option value=""></option>
            	<?php
					$sql2 = "SELECT * FROM car_wash_vendor WHERE active=1";
					$rs2 = mysql_query($sql2);
					while($row2=mysql_fetch_array($rs2)){
						if($_REQUEST['carWashVendor']==$row2['id']){
							echo '<option value="'.$row2['id'].'" selected="selected">'.$row2['name'].'</option>';
						}
						else{
							echo '<option value="'.$row2['id'].'">'.$row2['name'].'</option>';
						}
					}
				?>
            </select>
                    </td>
                    <td width="10%">Status:</td>
                    <td width="10%">
                    	<select style="background-color:#FAD090" name="status">
                        <option value=""></option>
            <option value="New" <?php if($_REQUEST['status']==='New') {echo 'selected="selected"';}?>>New</option>
            <option value="Pending Invoice" <?php if($_REQUEST['status']==='Pending Invoice') {echo 'selected="selected"';}?>>Pending Invoice</option>
            <option value="In Progress" <?php if($_REQUEST['status']==='In Progress') {echo 'selected="selected"';}?>>In Progress</option>
            <option value="Pending Payment" <?php if($_REQUEST['status']==='Pending Payment') {echo 'selected="selected"';}?>>Pending Payment</option>
            <option value="Closed" <?php if($_REQUEST['status']==='Closed') {echo 'selected="selected"';}?>>Closed</option>
            			</select>
                    </td>
                    <td width="20%" colspan="2">Date To:&nbsp;&nbsp;&nbsp;&nbsp;

                    	<input type="text" id="tdate" name="tdate" readonly/ size="13" value="<?php echo $tdate;?>"/>
              <button id="tdatebutton">
                <img src="anytime/calendar.png" alt="[calendar icon]"/>
              </button>
              <script>
                $('#tdatebutton').click(
                  function(e) {
                    $('#tdate').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();
                    e.preventDefault();
                  } );
              </script>
                    </td>
                 	<td colspan="5" align="right"><input type="submit" name="clear" value="Clear" style="width:85px"/></td>
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
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Attendee</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Location</td>
                        <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Job</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">Due</td>
			<td colspan="2" class="top-right-child" style="background-color:#ECF65C;" width="<?php echo $col4+$col4;?>">Status</td>

        </tr>
        <?php
			if(1){

				$sql = "SELECT * FROM `car_wash` WHERE `active`=1 ".$filter." order by STR_TO_DATE( `out_date_time` , '%m-%d-%Y %k:%i' ) DESC, id DESC";
				$rs = mysql_query($sql);
				$num_rows = mysql_num_rows($rs);
				$end = ($paging * $page)-1;
				$current_pt = ($paging * $page) - $paging;
				//echo $sql.'<br/>'.mysql_error();
				$sql = "SELECT * FROM `car_wash` WHERE `active`=1 ".$filter." order by STR_TO_DATE( `out_date_time` , '%m-%d-%Y %k:%i' ) DESC, id DESC LIMIT ".$current_pt.", ".$paging;
				$rs = mysql_query($sql);
			}
			$bg = 0;
			while($row = mysql_fetch_array($rs)){

		?>
        	<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>
                <td class="middle-left-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="car_wash_detail.php?id=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>
                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2+10;?>"><?php echo substr($row['out_date_time'],0,16);?></td>
             	<td colspan="2" class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2+$col3;?>"><?php
					$wash = $row['car_wash_vehicle_id'];
					$sql2 = "SELECT * FROM `car_wash_vehicle` WHERE `id`=$wash";
					$rs2 = mysql_query($sql2);
					$row2 = mysql_fetch_array($rs2);
					echo $row2['make'].' '.$row2['model'].' ('.$row2['license_plate'].')';

				?></td>
                <td colspan="2" class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo ($col3-15+$col2);?>"><?php
				if(is_numeric($row['request_id'])){
					$sql3 = "SELECT * FROM car_wash_request WHERE id = '".$row['request_id']."'";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					echo $row3['name'];
				}
				else { echo $row['request_id']; }
				?></td>
                	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>">
                <?php
					$sql3 = "SELECT * FROM car_wash_request WHERE id = '".$row['attendee_key_id']."'";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					echo $row3['name'];
				?>
              </td>
               	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>">
                <?php
					$sql3 = "SELECT * FROM `car_wash_vendor` WHERE id = '".$row['car_wash_vendor_id']."'";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					echo $row3['name'];
				?>
              </td>
              <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2;?>">
                <?php
					$sql3 = "SELECT * FROM `car_wash_group` WHERE id = '".$row['wash_type']."'";
					$rs3 = mysql_query($sql3);
					$row3 = mysql_fetch_array($rs3);
					echo $row3['name'];
				?>
              </td>
              	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col1;?>">
				 <?php
				 	echo number_format($row['rate'],2);
			?>
                </td>
            <td colspan="2" class="middle-right-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC;';} echo $ex;?> width="<?php echo $col4+$col4;?>">
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
							echo '<a style="color:#DCB272" href="/car_wash_list.php?page='.$start_page.'">'.$start_page.'</a> , <a style="color:#DCB272" href="/roadservice/car_wash_list.php?page='.($start_page+1).'"> More</a>';
						}
						else if($page == $start_page){
							echo '<<a style="color:#DCB272; font-weight: bold" href="/rental_list.php?page='.$start_page.'">'.$start_page.'</a>> , ';
						}
						else if ($less > 1){
							echo '<a style="color:#DCB272" href="/rental_list.php?page='.($less).'"> Less</a> , <a style="color:#DCB272" href="/roadservice/car_wash_list.php?page='.$start_page.'">'.$start_page.'</a> , ';
							$less = 0;
						}
						else{
							echo '<a style="color:#DCB272" href="/car_wash_list.php?page='.$start_page.'">'.$start_page.'</a> , ';
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
