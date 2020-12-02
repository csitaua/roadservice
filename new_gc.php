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

session_start();

echo menu();


?>

<form name="new_gc" action="rec_gc.php" method="post">
	<table width="1200" cellspacing="0">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h8 style="color:#FFF">New Service Request</h8></td>
        </tr>
        <tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        <td colspan="5">&nbsp;
        </td>
        </tr>
        <tr>
        	<td colspan="4" class="top-child_h" style="color:#148540;"><h4>General Claim</h4></td>
                    
        	
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Policy Number:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" value="<?php echo $pol?>" style="background-color:#FAD090" name="pol" size="25" /></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Job:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><select name="job" id="job" style="background-color:#FAD090"">
                    <?php
						$sql = "SELECT * FROM jobs_gc order by sort_order";
						$rs = mysql_query($sql);
						while($row=mysql_fetch_array($rs)){
							if($_REQUEST['job'] == $row['id']){
								echo '<option selected="selected" value="'.$row['id'].'">'.$row['description'].'</option>';	
							}
							else{
								echo '<option value="'.$row['id'].'">'.$row['description'].'</option>';	
							}
						}
					?>
                    </select></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Location:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="loc" id="loc" size="25" value="<?php echo $location;?>" /></td>
           	<td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
        </td>
        </tr>
        <tr >
        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">&nbsp;</td>
             <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
         <tr>
            <td class="middle-left-child" width="<?php echo $col1;?>">Attendee:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><select name="attendee" id="attendee" style="background-color:#FAD090">
            	<option value=""></option>
                    <?php
						$sql = "SELECT * FROM attendee WHERE id != 10 AND active=1 order by s_name ";
						$rs = mysql_query($sql);
						while($row=mysql_fetch_array($rs)){
							if($row['id'] == $attendee){
								echo '<option selected="selected" value="'.$row['id'].'">'.$row['s_name'].'</option>';
							}
							else{
								echo '<option value="'.$row['id'].'">'.$row['s_name'].'</option>';
							}
						}
					?>
                     <?php if($_SESSION['user_level'] >= POWER_LEVEL){ ?></select><?php } ?></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">District:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>"><select name="district" id="district" style="background-color:#FAD090">
            		<option value=""></option>
                    <?php
						$sql = "SELECT * FROM districts order by district";
						$rs = mysql_query($sql);
						while($row=mysql_fetch_array($rs)){
							if($row['id'] == $district){
								echo '<option selected="selected" value="'.$row['id'].'">'.$row['district'].'</option>';
							}
							else{
								echo '<option value="'.$row['id'].'">'.$row['district'].'</option>';
							}
						}
					?>
                    </select>            
            </td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Requested By:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>">
           
            <input type="text" style="background-color:#FAD090" name="requestedBy" id="requestedBy" size="23" maxlength="50"/> </td>
           	<td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;
            </td>
      	</tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
           	<td class="middle-none-child" width="<?php echo $col2;?>">&nbsp;</td>
           <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Add. Phone:</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090;" name="addphone" id="addphone" size="18" value="<?php echo $addPhone;?>"/></td>
             <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
           	<td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-none-child" width="<?php echo $col2;?>"><select name="tow_reason" id="tow_reason" style="background-color:#FAD090;display:none;">
            	<option value=""></option>
            	<?php
					$sql4 = "SELECT * FROM `towing_reason` ORDER BY `description`";
					$rs4 = mysql_query($sql4);
					while($row4 = mysql_fetch_array($rs4)){
				?>
                	<option value="<?php echo $row4['id'];?>"><?php echo $row4['description'];?></option>
                <?php } ?>
            </select></td>
            <td class="middle-none-child" width="<?php echo $col1;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">&nbsp;</td>
        </tr>
        <tr>
       		<td class="middle-left-child" width="<?php echo $col1;?>">Notes:</td>
       		<td class="middle-right-child" width="<?php echo $col2;?>" colspan="3"><textarea name="notes" cols="50" rows="4" style="background-color:#FAD090"></textarea></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Status:</td>
        	<td class="middle-none-child" width="<?php echo $col2;?>">
            <select name="status" style="background-color:#FAD090">
            	<?php
					$sql = "SELECT * FROM `status` WHERE id = 1 OR id=25";
					$rs = mysql_query($sql);
					while($row = mysql_fetch_array($rs)){
						echo '<option value="'.$row['id'].'">'.$row['status'].'</option>';	
					}
				?>
           	</select>
            </td>
        	<td class="middle-right-child" colspan="2" align="right">&nbsp;</td>
        </tr>
        <tr>
        	<td class="bottom-left-child" width="<?php echo $col1;?>">Date Time:</td>
        	<td class="bottom-center-child" width="<?php echo $col2;?>">
            	<input type="text" id="opendt" name="opendt" readonly value="<?php echo $open;?>"/>
  <button id="opendtbutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#opendtbutton').click(
      function(e) {
        $('#opendt').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
  </script>
            </td>
            <td class="bottom-right-child" colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
        </tr>
        <tr><td colspan="4">&nbsp;</td></td>
    </table>
</form>
<script type="text/javascript" src="js/functions.js">

</script>
</body>
</html>