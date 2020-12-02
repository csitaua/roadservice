<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();


echo menu();
$id = $_REQUEST['sc'];

$col1 = 75;
$col2 = 200;
$history_item = 3;

$sql = "SELECT * FROM service_req WHERE id = '$id'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$st ='';
if(!checkAdmin()){
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
				$sql2 = "SELECT * FROM vehicles_2 WHERE LicPlateNo = '$lic' ORDER BY STR_TO_DATE( `Date_Effective` , '%m/%d/%Y' ) DESC";	
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
                    	<td width="<?php echo $vcol1;?>%">Vin:</td>
                        <td width="<?php echo $vcol2;?>%"><?php echo $row2['VinNo'];?></td>
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
                <tr><td colspan="2">&nbsp;</td></tr>
                <tr><td colspan="2" style="color:#148540" align="center">History</td></tr>
                <?php
					$sql2 = "SELECT * FROM service_req WHERE id != '$id' AND `a_number`='$lic' AND `delete` = 0 order by STR_TO_DATE( `opendt` , '%m-%d-%Y' ) DESC, id DESC LIMIT 0, 3";
					$rs2 = mysql_query($sql2);
					if(mysql_num_rows($rs2)!=0){
						while($row2 = mysql_fetch_array($rs2)){
				?>
                			<tr>
                            	<td width="<?php echo $vcol1;?>%">ID:</td>
                        		<td width="<?php echo $vcol2;?>%"><a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row2['id'];?>"/><?php echo str_pad($row2['id'],5,'0',STR_PAD_LEFT);?></td>
                            </tr>
                            <tr>
                    			<td width="<?php echo $vcol1;?>%">Date:</td>
                        		<td width="<?php echo $vcol2;?>%"><?php echo substr($row2['opendt'],0,16);?></td>
                    		</tr>
                            <tr>
                    			<td width="<?php echo $vcol1;?>%">Job:</td>
                        		<td width="<?php echo $vcol2;?>%"><?php 
									$jid = $row2['job'];
									$sql3 = "SELECT * FROM jobs where id = '$jid'";
									$rs3 = mysql_query($sql3);
									$row3 = mysql_fetch_array($rs3);
									echo $row3['description'];
								?></td>
                    		</tr>
                <?php	} //End While Loop
					} //End if Loop
				?>
            </table>
            </td>
        </tr>
        <tr>
        	<td colspan="4">Time Requested: <?php echo $row['opendt'];?>
        </tr>
        <?php
			if($row['status']==2){ //Closed
		?>
         <tr>
        	<td colspan="4">Time Closed: <?php echo $row['closedt'];?>
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
        <tr>
        	<td width="<?php echo $col1;?>">Car:</td>
            <td width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="car" size="25" value="<?php echo $row['car'];?>" /></td>
            <td width="<?php echo $col1;?>">Car Number:</td>
            <td width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="num" size="15" value="<?php echo $row['a_number'];?>"/></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Location:</td>
            <td width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="loc" size="25" value="<?php echo $row['location'];?>"/></td>
            <td width="<?php echo $col1;?>">Job:</td>
            <td width="<?php echo $col2;?>">
            <?php if(checkAdmin()){
			
			?>
            <select name="job" style="background-color:#FAD090">
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
            <?php if (checkAdmin()){
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
				if(checkAdmin()){
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
             <td width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" value="<?php echo number_format($row['charged'],2);?>" style="background-color:#FAD090; text-align:right" name="charged" size="10"/> Afl.</td>
            <td width="<?php echo $col1;?>">Policy Number:</td>
             <td width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="pol" size="20" value="<?php echo $row['pol'];?>"/></td>
        </tr>
        <tr>
       		<td width="<?php echo $col1;?>">Add. Notes:</td>
       		<td width="<?php echo $col2;?>" colspan="3"><?php echo $row['notes'];?><br/><textarea name="notes" cols="50" rows="4" style="background-color:#FAD090"></textarea></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Status:</td>
        	<td width="<?php echo $col2;?>"><?php 
				if($row['status']==1 || checkAdmin()){
			?><select name="status" style="background-color:#FAD090">
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
            <input type="hidden" name="status" value="<?php echo $row['status'];?>"/>
            <input name="statusd" style="background-color:#FAD090" readonly="readonly" value="<?php
				$sql2 = "SELECT * FROM status WHERE id = '$row[status]'";
				$rs2 = mysql_query($sql2);
				$row2 = mysql_fetch_array($rs2);
            	echo $row2['status'];
			?>"/>
            <?php }?></td>
            
            <?php 
			if(!checkAdmin() && !checkView() && $row['status'] !=1){
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
        <tr>
        	<td width="<?php echo $col1;?>">Picture:</td>
         	<td colspan="3"><input name="image_upload_box" type="file" id="image_upload_box" size="40" /></td>
         </td>
        <?php 
			if($row['status']==1 || checkAdmin()){
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
           <?php if(!checkView()){ ?>
            <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
            <?php } ?>
          
        </tr>
        <?php
			if($row['master_sc']==0 && checkAdmin()){
		?>
        <tr><td colspan="4" align="right"><input type="submit" name="tow" value="Tow"/></td></tr>  
        <?php
			}
		?>
        </tr>
        <?php if(checkAdmin()) { ?>
        <tr><td colspan="4" align="right"><input type="submit" name="delete" value="Delete"/></td></tr>
        <?php } ?>
        <tr><td colspan="4">
        	<table width="100%">
        <?php
			}
		?>
         <?php
			if($row['master_sc']==0 && !checkAdmin()){
		?>
        <tr><td colspan="4" align="right"><input type="submit" name="tow" value="Tow"/></td></tr>  
        <?php
			}
		?>
        <tr><td colspan="4">&nbsp;</td></tr>  
        <?php
			$dirname = "rrimage/".$id;
			$thumbs = "rrthumbs/".$id;
			$images = scandir($dirname);
			$ignore = Array(".", "..");
			$r = 1;
			foreach($images as $curimg){
				if(!in_array($curimg, $ignore)) {
					if($r==1){
						echo'<tr>';	
					}
					if(checkAdmin()){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="100" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a></td>';
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

			if (is_dir('rrimage/'.$id) && checkAdmin()) {
				echo '<a style="color:#DCB272" href="zip_download.php?dir='.$id.'">Download All Picture(s)</a>';
			}
		?></td></tr>
        <tr><td colspan="4">&nbsp;</td></tr>
    </table>
</form>
<?php if(!checkAdmin()){?>
<script  type="text/javascript">
 var frmvalidator = new Validator("edit_sc");
 <?php if($row['job'] == 7 && !is_dir('rrimage/'.$id)){ ?>
 	frmvalidator.addValidation("image_upload_box","req","Accident, you are required to upload at least one image");
  <?php } ?>
 frmvalidator.addValidation("closeddt","req","Please Enter Closed Date Time");
</script>
<?php } ?>
</body>
</html>