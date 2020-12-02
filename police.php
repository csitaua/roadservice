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
	<table width="1000" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><div class="rounded_h"><h3>Service Request # <?php  echo str_pad($id,5,'0',STR_PAD_LEFT);?></h3></div></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <?php if($row['master_sc']!=0){
		?>
        <tr>
        	<td class="top-child" colspan="4">Service request came from#<a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['master_sc'];?>"/><?php echo str_pad($row['master_sc'],5,'0',STR_PAD_LEFT);?></td>
       		<td>&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-child" colspan="4">Time Inserted: <?php echo substr($row['timestamp'],0,-3);?></td>
        <?php
		}
		else{
		?>
        <tr>
        	<td class="top-child" colspan="4">Time Inserted: <?php echo substr($row['timestamp'],0,-3);?></td>
       	<?php
		}
		?>
            
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
               	colspan="4">Time Requested: 
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
        	<td <?php if($row['status']!=2 && $row['status']!=3) {
						echo 'class="bottom-child"';
					}
					else{
						echo 'class="middle-child"';	
					}
				?> colspan="4">Time Requested: <?php echo $row['opendt'];?>
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
        	<td class="bottom-child" colspan="4">Time Closed: <?php echo $row['closedt'];?>
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
        	<td class="bottom-child" colspan="4">Time Cancelled: <?php echo $row['closedt'];?>
        </tr>	
        <?php
			}
		?>
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr>
        	<td colspan="2" class="top-child_h" style="color:#148540" align="center">Call Information</td>
            <td colspan="2" class="top-child_h" style="color:#148540" align="center">Vehicle Insurance Information</td>
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
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="num" size="15" value="<?php echo $row['a_number'];?>"/></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Location:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php echo $st;?> type="text" style="background-color:#FAD090" name="loc" size="25" value="<?php echo $row['location'];?>"/></td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Car:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="car" size="25" value="<?php echo $row['car'];?>" /></td>
        </tr>
        <tr>
          	<td class="middle-left-child" width="<?php echo $col1;?>"><?php
            	if($row['job']!=7){ echo 'Location to:';}
				else { echo 'Accident Info:';}
			?></td>
          	<td class="middle-right-child" width="<?php echo $col2;?>"><input  <?php if(strcmp($row['toloc'],'')!=0){ echo $st;}?> type="text" style="background-color:#FAD090; <?php if($row['job'] != 3 && $row['job'] != 12 && $row['job'] != 13 && $row['job'] != 14  && $row['job'] != 16  && $row['job'] != 19  && $row['job'] != 20  && $row['job'] != 22 && $row['job'] != 29 && $row['job'] != 32 && $row['job'] != 33){ echo 'display:none'; }?>" name="toloc" id="loc" size="25" value="<?php echo $row['to_location'];?>"/>
            <?php 
				if($row['job']==7 || $row['job']==8 || $row['job']==18 || $row['job']==23 || $row['job']==28 || $row['job'] == 15 ){
			?>
            	<div class="buttonwrapper">
					<a class="squarebutton" target="_blank" href="accident_extra.php?sc=<?php echo $id?>"><span>Extra Information</span></a>
				</div>
            <?php } ?>
            </td>
            <td class="middle-left-child" width="<?php echo $col1;?>">Vin:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input <?php echo $st;?> type="text" style="background-color:#FAD090" name="vin" size="25" value="<?php echo $row['vin'];?>" /></td>
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
            <td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
             <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
           	
        </tr>
        <tr>
        	 <td class="middle-left-child" width="<?php echo $col1;?>">Attendee:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
            	<input readonly type="text" value="<?php
						$aid = $row['attendee_id'];
						$sql2 = "SELECT * FROM attendee WHERE `id`='$aid'";
						$rs2 = mysql_query($sql2);
						$row2 = mysql_fetch_array($rs2);
						echo $row2['s_name'];
					?>" name="attendeed" style="background-color:#FAD090; text-align:left" size="20" />
            	<input type="hidden" name="attendee" value="<?php echo $row['attendee_id'];?>"/>
           
            </td>
           	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
            </td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $row['requestedBy'];?>" style="background-color:#FAD090;" name="requestedBy" size="20" maxlength="50"/></td>
        	 <td class="bottom-left-child" width="<?php echo $col1;?>">&nbsp;</td>
       		<td class="bottom-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Add. Phone:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $row['AddPhone'];?>" style="background-color:#FAD090;" name="addphone" size="18"/></td>
           	<td colspan="2">&nbsp;</td>
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
            <td  colspan="2"  align="center">&nbsp;</td>
      	</tr>
        <tr>
        	<?php if($tp && $_SESSION['user_level']>3){ ?>
            	<td class="middle-left-child" width="<?php echo $col1;?>">TP Charge:</td>
          	  	<td class="middle-right-child" width="<?php echo $col2;?>"><input type="number" value="<?php echo number_format($row['tpCharged'],2,'.','')?>" name="tpCharged" style="background-color:#FAD090; text-align:right" size="10" maxlength="7"/>Afl.&nbsp;Rec. <input type="checkbox" name="tpChargedReceived" <?php if($row['tpChargedReceived']){echo 'checked="checked"';} ?> /></td>
				
			<?php } 
			if (1){
			?>
            	<td class="middle-left-child" width="<?php echo $col1;?>"></td>
       			<td class="middle-right-child" width="<?php echo $col2;?>"></td>
			<?php
        	}?>
        </tr> 
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Vehicle Present:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input  type="checkbox" id="present"  style="background-color:#FAD090" name="present" <?php if($row['present'] ==1 || !$row['present']){echo 'checked="checked"';}?>/>
            </td>
             <?php if (1){?>
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
        	<td class="middle-child" colspan="2">&nbsp;</td>
             <?php if (1){?>
             <td class="middle-left-child" width="<?php echo $col1;?>">Invoice #:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="invoice" size="20" maxlength="30" value="<?php echo $row['invoice'];?>"/>
            </td>
            <?php } ?>
      	</tr>
        <tr>
        	<td class="bottom-child" colspan="2">&nbsp;</td>
              <?php if (1){?>
            <td class="bottom-left-child" width="<?php echo $col1;?>">PO Number:</td>
            <td class="bottom-right-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="po_number" size="20" value="<?php echo $row['po_number'];?>"/>
            </td>
            <?php } ?>
        </tr>
        <tr>
        	<td class="top-left-child" width="<?php echo $col1;?>">Tow Reason:</td>
            <td class="top-right-child" width="<?php echo $col2;?>" colspan="3"><select name="tow_reason" id="tow_reason" style="background-color:#FAD090; <?php if($row['job'] != 3 && $row['job'] != 12 && $row['job'] != 13 && $row['job'] != 14  && $row['job'] != 16  && $row['job'] != 19  && $row['job'] != 20  && $row['job'] != 22 && $row['job'] != 32 && $row['job'] != 33){ echo 'display:none'; }?>">
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
       		<td class="bottom-right-child" width="<?php echo $col2;?>" colspan="3"><textarea required name="notes" cols="50" rows="4" style="background-color:#FAD090"><?php echo stripcslashes($row['notes']);?></textarea></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">&nbsp;</td>
         	<td colspan="3">&nbsp;</td>
         </td>
        <?php 
			if($row['status']==1 || $row['status']==4 || $_SESSION['user_level'] >= POWER_LEVEL){
		?>
        	 <tr>
 
           <?php if(1){ ?>
            <td colspan="2" align="right">&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
            <?php } ?>
        </tr>
        <tr><td colspan="5">
        	<table width="100%" cellspacing="0">
        <?php
			}
		?>
  
        <tr><td colspan="5">&nbsp;</td></tr> 
        
        <tr><td class="child" colspan="5"><table width="100%" cellspacing="0">
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
				echo '<tr><td colspan="4"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'">'.$doc.'</a>';
				if($_SESSION['user_level'] == ADMIN_LEVEL){
					echo '&nbsp;<a style="color:#DCB272" href="delete.php?file='.$docs.'/'.urlencode($doc).'&sc='.$id.'">Delete</a>';	
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
					
					if($r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="100" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/></td>';
					}
					else if(1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="100" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/></td>';
					}
					else if($r==1){
						echo '<td width="25%"><img width="100" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
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

			if (is_dir('rrimage/'.$id)) {
				echo '<a style="color:#DCB272" href="zip_download.php?dir=rrimage/'.$id.'">Download All Image(s)</a>';
			}
			?></td></tr>            
            </table>
      		
       	<?php if($row['master_sc']){ ?>
        <tr><td colspan="5">&nbsp;</td></tr> 
        
        <tr><td class="child" colspan="5"><table width="100%" cellspacing="0">
        <?php
			$id = $row['master_sc'];
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
				echo '<tr><td colspan="4"><a style="color:#DCB272" href="download.php?file='.$docs.'/'.urlencode($doc).'">'.$doc.'</a>';
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
					
					if($_SESSION['user_level'] >= POWER_LEVEL && $r==1){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="100" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($_SESSION['user_level'] >= POWER_LEVEL){
						echo '<td width="25%"><a href="download.php?file='.$dirname.'/'.$curimg.'"><img width="100" src="download.php?file='.$thumbs.'/'.$curimg.'" /></a><br/><a style="color:#DCB272" href="delete.php?file='.$dirname.'/'.$curimg.'&sc='.$id.'">Delete</a></td>';
					}
					else if($r==1){
						echo '<td width="25%"><img width="100" src="download.php?file='.$thumbs.'/'.$curimg.'" /></td>';
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

			if (is_dir('rrimage/'.$id) && $_SESSION['user_level'] >= POWER_LEVEL) {
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
 <?php if($row['job'] == 7 && !is_dir('rrimage/'.$id)){ ?>
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
		if ( txt.toString()=='3' || txt.match('12') || txt.match('13') || txt.match('14') || txt.match('16') || txt.match('19') || txt.match('22') || txt.match('20') || txt.match('29') || txt.match('32') || txt.match('32')) {
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
		if( document.getElementById('money').checked){
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
		if(document.getElementById('licenseNo').value.length != 0){
			window.open('ins_drivers_license.php?license='+licensenumber+'&pol='+pol);	
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