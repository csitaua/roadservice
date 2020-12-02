<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

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

if(isPolice()){
	$fjob=32;	
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

if($_REQUEST['bill_to']){
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
			$bill_to = 100;
			$licenseNo = '';
			$insured_not = 0;
			$district = 0;
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
	}
}

$sql2 = "SELECT COUNT(*) as t FROM vehicles_2 WHERE VehStatus ='A'";
$rs2 = mysql_query($sql2);
$row2 = mysql_fetch_array($rs2);

?>

<table width="1200" align="center" cellspacing="0">
    	<tr>
        	<td colspan="12" align="center" ><h8 style="color:#FFF">Service List</h8></td>
        </tr>
         <tr><td colspan="12" align="right" style="color:#FFF">Total Insured Vehicles: <?php echo $row2['t'];?></td></tr>
        <tr><td colspan="12" class="child" >
        <form name="filter" action="" method="post">
        	<table width="100%">
            	<tr>
                	<td colspan="4">
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
                            $sql = "SELECT * FROM status ";
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
                    </select> Date From: <input type="text" id="sdate" name="sdate" readonly="readonly"/ size="13" value="<?php echo $sdate;?>"/>
              <button id="sdatebutton">
                <img src="anytime/calendar.png" alt="[calendar icon]"/>
              </button>
              <script>
                $('#sdatebutton').click(
                  function(e) {
                    $('#sdate').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();
                    e.preventDefault();
                  } );
              </script> To <input type="text" id="edate" name="edate" readonly="readonly" size="13" value="<?php echo $edate;?>"/>
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
    	<td width="150">A-Number:</td>
        <td width="350"><input type="text" name="anumber" style="background-color:#FAD090" value="<?php echo $anumber;?>" onkeypress="submit_form(event);"/></td>
        <td width="150">Bill To:</td>
        <td width="350">
        	<select name="bill_to" style="background-color:#FAD090;width:225px">
             <option <?php if($bill_to==100){echo 'selected="selected"';}?> value="100">All</option>
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
   	</tr>
     <tr>
    	<td width="150">Vin No:</td>
        <td><input type="text" name="vin" size="30" style="background-color:#FAD090" value="<?php echo $vin;?>" onkeypress="submit_form(event);"/></td>
         <td width="150">Drivers License:</td>
        <td width="350"><input type="text" name="licenseNo" style="background-color:#FAD090" value="<?php echo $licenseNo;?>" onkeypress="submit_form(event);"/></td>
    </tr>
    <tr>
    	<td width="150">Client Number:</td>
        <td><input type="text" name="clientno" style="background-color:#FAD090" value="<?php echo $clientno;?>" onkeypress="submit_form(event);"/></td>
         	<td width="150">Insured:</td>
        	<td width="350"><select name="insured_not" style="background-color:#FAD090">
            	<option <?php if($insured_not ==0){ echo 'selected="selected"';} ?> value="0">All</option>
                <option <?php if($insured_not ==1){ echo 'selected="selected"';} ?> value="1">Yes</option>
                <option <?php if($insured_not ==2){ echo 'selected="selected"';} ?> value="2">No</option>
            </select>
            </td>
    </tr>
    <tr>
    	<td width="150">Money Received:</td>
        <td><select name="mr" style="background-color:#FAD090">
           <option <?php if($mr==0){echo 'selected="selected"';}?> value="0">All</option>
           <option <?php if($mr==-1){echo 'selected="selected"';}?> value="-1">No</option>
           <option <?php if($mr==1){echo 'selected="selected"';}?> value="1">Yes</option>          
       	</select></td>
        <td width="150">District:</td>
        <td width="350">
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
   	</tr>
    <tr>
    	<td width="150">ID:</td>
        <td><input type ="text" name="sid" style="background-color:#FAD090" value="<?php echo $sid;?>" size="7" onkeypress="submit_form(event);"/></td>
        <td colspan="2">&nbsp;</td>
   	</tr>
    <tr>
    	<td width="150">Receipt:</td>
        <td><input type ="text" name="receipt" style="background-color:#FAD090" value="<?php echo $receipt;?>" size="8" onkeypress="submit_form(event);"/></td>
        <td colspan="2">&nbsp;</td>
   	</tr>
    <tr>
    	<td colspan="4">
            <input type="submit" name="filter" value="Filter" default/>&nbsp;
            <input type="submit" name="clear" value="Clear"/>
       	</td>
  	</table>
     </form>
      	</td></tr>
        <tr><td colspan="12">&nbsp;</td></tr>
        <tr>
        	<td class="top-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2+15;?>">Date</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2-15;?>">Job</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col3;?>">A Number</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Car</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Cov/Use</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Location</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Attendee</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Charged</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Insured</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Picture</td>
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
				if($fjob != 0){
					$filter = $filter.' AND job = '.$fjob;		
				}
				if($faid != 0){
					$filter = $filter.' AND attendee_id = '.$faid;
				}
				if($sid != 0){
					$filter = $filter.' AND id = '.$sid;	
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
					$filter = $filter." AND licenseNo = '$licenseNo'";
				}
				
				if($bill_to!=100){
					$filter = $filter." AND bill_to = '$bill_to'";
				}
				
				if($mr != 0){
					if($mr == 1){
						$filter = $filter." AND `money_delivered` != 0 AND `charged` > 0";
					}
					else{ //$mr == -1
						$filter = $filter." AND `money_delivered` = 0 AND `charged` > 0 AND paymentType != 'Bank Transfer' AND paymentType!= 'Office' AND paymentType!= 'Agreement' AND `po_received`!=1";
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
				
		?> 
        	<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>
                <td class="middle-left-child" <?php 
				if($row['master_sc']!=0){
					echo 'style="background-color:#6F3"';
				}
				else if($row['status']==4){
					echo 'style="background-color:#EB6C2C"';
				}
				else if(( !is_dir('rrdocs/'.$row['id']) &&  !is_dir('rrimage/').$row['id'] ) && $row['job']==7){
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
				
				?> <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>
                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2+10;?>"><?php echo substr($row['opendt'],0,16);?></td>
                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2-15;?>"><?php 
					$jid = $row['job'];
					$sql2 = "SELECT * FROM jobs where id = '$jid'";
					$rs2 = mysql_query($sql2);
					$row2 = mysql_fetch_array($rs2);
					echo $row2['description'];
				?></td>
                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>"><?php echo $row['a_number'];?></td>
             	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2;?>"><?php echo $row['car'];?></td>
                 <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>">
                	<?php
						$PolicyNo = $row['pol'];
						$LicPlateNo = $row['a_number'];
						$sql3 = "SELECT * FROM `vehicles_2` WHERE `PolicyNo`='$PolicyNo' AND `LicPlateNo`='$LicPlateNo'";
						$rs3 = mysql_query($sql3);
						$row3 = mysql_fetch_array($rs3);
						if($row3){
							echo $row3['VehCoverage'].'/'.$row3['VehUse'];
						}
						else{
							$sql3 = "SELECT * FROM `non_client_extra` WHERE `id` = '$LicPlateNo'";
							$rs3 = mysql_query($sql3);
							$row3 = mysql_fetch_array($rs3);	
							if($row3){
								echo $row3['vehicle_coverage'].'/'.$row3['vehicle_use'];
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
                	<?php if($row['charged']>0) echo '<b>'.number_format($row['charged'],2).'</b>';else{echo number_format($row['charged'],2);}
					if($row['receipt']>0){
						echo ' R:'.$row['receipt'];	
					}
					?>
                </td>
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
						
						if(file_exists("rrimage/".$row['id']) || file_exists("rrimage/".$row['accident_link']) || file_exists("rrimage/".$row['accident_link2']) || file_exists("rrimage/".$row['accident_link3'])){
							echo '<b>Yes</b>';
						}
						else{
							echo 'No';
						}
					?>
                </td>
                <td class="middle-right-child" <?php if($new==1){echo $news;} else if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col4;?>"><?php 
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
							echo '<a style="color:#DCB272" href="/roadservice/index.php?page='.$start_page.'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'">'.$start_page.'</a> , <a style="color:#DCB272" href="/roadservice/index.php?page='.($start_page+1).'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'&district='.$district.'"> More</a>';
						}
						else if($page == $start_page){
							echo '<<a style="color:#DCB272; font-weight: bold" href="/roadservice/index.php?page='.$start_page.'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'&district='.$district.'">'.$start_page.'</a>> , ';
						}
						else if ($less > 1){
							echo '<a style="color:#DCB272" href="/roadservice/index.php?page='.($less).'"> Less</a> , <a style="color:#DCB272" href="/roadservice/index.php?page='.$start_page.'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'&district='.$district.'">'.$start_page.'</a> , ';
							$less = 0;
						}
						else{
							echo '<a style="color:#DCB272" href="/roadservice/index.php?page='.$start_page.'&aid='.$faid.'&status='.$fstatus.'&job='.$fjob.'&sdate='.$sdate.'&edate='.$edate.'&district='.$district.'">'.$start_page.'</a> , ';
						}
						$start_page++;
					}
				?>&nbsp; Number of Results: <?php echo $num_rows;?>
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