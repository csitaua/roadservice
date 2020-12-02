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
	$filter = $filter." AND STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('".$sdate."','%m-%d-%Y') ";
}
if($edate!==''){
	$filter = $filter." AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('".$edate."','%m-%d-%Y') ";
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
                	<td width="10%">&nbsp;</td>
                	<td width="10%">&nbsp;
                    </td>
                    <td width="10%">&nbsp;</td>
                	<td width="10%">&nbsp;</td>
                    
            	 	<td colspan="7">&nbsp;
                    </td>
        		 </tr>
                </table>
            </td>
         </tr>
           
         </form>
        <tr><td colspan="12">&nbsp;</td></tr>
        <tr>
        	<td class="top-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2+15;?>">Date of Loss<br/>Date Closed</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col3;?>">Policy No</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>"></td>
            <td colspan="2" class="top-center-child" style="background-color:#ECF65C;" width="<?php echo ($col4-15+$col2);?>">Location Survey</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo ($col3);?>">Claims Handler</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Claims No</td>
                        <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col2;?>">Date of Loss</td>
            <td class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">&nbsp;</td>
			<td  class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">&nbsp;</td>
            <td  class="top-center-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">&nbsp;</td>
            <td class="top-right-child" style="background-color:#ECF65C;" width="<?php echo $col4?>">Status</td>

        </tr>  
        <?php
			if(1){
				$filter='';
				$sql = "SELECT * FROM service_gc WHERE `delete` =0 ".$filter." order by STR_TO_DATE( `opendt` , '%m-%d-%Y %k:%i' ) DESC, id DESC";
				
				$rs = mysql_query($sql);
				$num_rows = mysql_num_rows($rs);
				$end = ($paging * $page)-1;
				$current_pt = ($paging * $page) - $paging;
				$sql = $sql = "SELECT * FROM service_gc WHERE `delete` =0 ".$filter." order by STR_TO_DATE( `opendt` , '%m-%d-%Y %k:%i' ) DESC, id DESC LIMIT ".$current_pt.", ".$paging;
				$rs = mysql_query($sql);
			}
			$bg = 0;
			
			while($row = mysql_fetch_array($rs)){
				
				list($month,$day,$year) = explode('-',substr($row['opendt'],0,10));
				
				
				$sql7 = "SELECT * FROM VW_POLICIES WHERE PolicyNo LIKE '".$row['pol']."' ORDER BY 
				CASE	WHEN Status='A' THEN 1
						WHEN Status='L' THEN 2
						WHEN Status='C' THEN 3
				End
				, Date_Renewal DESC, PolicyNo DESC";
				$params = array();
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$rs7 = sqlsrv_query($conn,$sql7,$params,$options);
				$row7 = sqlsrv_fetch_array($rs7);
					
					list($date,$time) = explode(' ',$row['opendt']);
					$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);
					if($row['closedt']!=='' && $row['closedt'] !== NULL){
						list($month,$day,$year) = explode('-',substr($row['closedt'],0,10));					
						list($date,$time) = explode(' ',$row['closedt']);
						$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);		
					}
					else{
						$date2 = new DateTime(date("Y-m-d H:i"));
					}
					$day_diff=0;
					$interval = $date1->diff($date2);
					$day_diff=$interval->days;
					$date1 = new DateTime($year.'-'.$month.'-'.$day);
					if($row['closedt']!=='' && $row['closedt'] !== NULL){
						list($month,$day,$year) = explode('-',substr($row['closedt'],0,10));
						list($date,$time) = explode(' ',$row['closedt']);
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
                <td class="middle-left-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="edit_gc.php?sc=<?php echo $row['id'];?>"><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?>
                </a>
                
                </td>
                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2+10;?>"><?php echo substr($row['opendt'],0,16).'<br/><a style="color:blue">'.substr($row['closedt'],0,16).'</a>';?></td>
             	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col3;?>"><?php 
					echo $row['pol'];
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
              <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col2;?>"><?php echo $row['opendt'];?></td>
              	<td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col1;?>"></td>
                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> width="<?php echo $col4;?>"></td>
                <td class="middle-none-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC;';} ?> width="<?php echo $col4;?>"></td>
            <td class="middle-right-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC;';} ?> width="<?php echo $col4;?>">
                <?php 
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
							echo '<a style="color:#DCB272" href="/roadservice/survey_list.php?page='.$start_page.'&edate='.$edate.'&sdate='.$sdate.'&adjuster='.$adjuster.'&status='.$status.'&rep_damage='.$_REQUEST['rep_damage'].'&survey_duration='.$_REQUEST['survey_duration'].'">'.$start_page.'</a> , <a style="color:#DCB272" href="/roadservice/survey_list.php?page='.($start_page+1).'"> More</a>';
						}
						else if($page == $start_page){
							echo '<a style="color:#DCB272; font-weight: bold" href="/roadservice/survey_list.php?page='.$start_page.'&edate='.$edate.'&sdate='.$sdate.'&adjuster='.$adjuster.'&status='.$status.'&rep_damage='.$_REQUEST['rep_damage'].'&survey_duration='.$_REQUEST['survey_duration'].'">'.$start_page.'</a>> , ';
						}
						else if ($less > 1){
							echo '<a style="color:#DCB272" href="/roadservice/survey_list.php?page='.($less).'"> Less</a> , <a style="color:#DCB272" href="/roadservice/survey_list.php?page='.$start_page.'&edate='.$edate.'&sdate='.$sdate.'&adjuster='.$adjuster.'&status='.$status.'&rep_damage='.$_REQUEST['rep_damage'].'&survey_duration='.$_REQUEST['survey_duration'].'">'.$start_page.'</a> , ';
							$less = 0;
						}
						else{
							echo '<a style="color:#DCB272" href="/roadservice/survey_list.php?page='.$start_page.'&edate='.$edate.'&sdate='.$sdate.'&adjuster='.$adjuster.'&status='.$status.'&rep_damage='.$_REQUEST['rep_damage'].'&survey_duration='.$_REQUEST['survey_duration'].'">'.$start_page.'</a> , ';
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