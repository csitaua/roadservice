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

$status = $_REQUEST['status'];
$job = $_REQUEST['job'];
$aid = $_REQUEST['aid'];

echo menu();



?>

<table width="900">
    	<tr>
        	<td colspan="11" align="center" style="border:0;color:#148540"><h3>Service Requests</h3></td>
        </tr>
        <tr><td colspan="11" >
        <form name="filter" action="" method="post">
        	Attendee: <select name="aid" style="background-color:#FAD090">
        	<option <?php if($aid==0){echo 'selected="selected"';}?> value="0">All</option>
            <?php
				$sql = "SELECT * FROM attendee WHERE id != 10 order by s_name ";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					if($aid == $row['id']){
						echo '<option selected="selected" value="'.$row['id'].'">'.$row['s_name'].'</option>';	
					}
					else{
						echo '<option value="'.$row['id'].'">'.$row['s_name'].'</option>';	
					}
				}
			?>
        </select> Status: 
        <select name="status" style="background-color:#FAD090">
           <option <?php if($status==0){echo 'selected="selected"';}?> value="0">All</option>
            <?php
				$sql = "SELECT * FROM status ";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					if($status == $row['id']){
						echo '<option selected="selected" value="'.$row['id'].'">'.$row['status'].'</option>';
					}
					else{
						echo '<option value="'.$row['id'].'">'.$row['status'].'</option>';	
					}
				}
			?>
        </select> Jobs: <select name="job" style="background-color:#FAD090">
           <option <?php if($job==0){echo 'selected="selected"';}?> value="0">All</option>
            <?php
				$sql = "SELECT * FROM jobs ";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					if($job == $row['id']){
						echo '<option selected="selected" value="'.$row['id'].'">'.$row['description'].'</option>';	
					}
					else{
						echo '<option value="'.$row['id'].'">'.$row['description'].'</option>';	
					}
				}
			?>
        </select>
       	<input type="submit" name="submit" value="Filter"/>
      	</form>
      	</td></tr>
        <tr>
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
        <?php
			if(checkEXT()){
				
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
			}
			else{
				$filter = '';
				if($status != 0){
					$filter = $filter.' AND status = '.$status;	
				} 
				if($job != 0){
					$filter = $filter.' AND job = '.$job;		
				}
				if($aid != 0){
					$filter = $filter.' AND attendee_id = '.$aid;
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
                <td <?php if($row['master_sc']!=0){
				echo 'style="background-color:#6F3"';
			}?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>
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
        <tr><td colspan="11">&nbsp;</td></tr>
        <tr>
        	<td colspan="11">Page 
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
							echo '<a style="color:#DCB272" href="/roadservice/index.php?page='.$start_page.'">'.$start_page.'</a> , <a style="color:#DCB272" href="/roadservice/index.php?page='.($start_page+1).'"> More</a>';
						}
						else if($page == $start_page){
							echo '<<a style="color:#DCB272; font-weight: bold" href="/roadservice/index.php?page='.$start_page.'">'.$start_page.'</a>> , ';
						}
						else if ($less > 1){
							echo '<a style="color:#DCB272" href="/roadservice/index.php?page='.($less).'"> Less</a> , <a style="color:#DCB272" href="/roadservice/index.php?page='.$start_page.'">'.$start_page.'</a> , ';
							$less = 0;
						}
						else{
							echo '<a style="color:#DCB272" href="/roadservice/index.php?page='.$start_page.'">'.$start_page.'</a> , ';
						}
						$start_page++;
					}
				?>
            </td>
        </tr>
 </table>

</body>
</html>