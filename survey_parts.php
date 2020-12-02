<?php
//survey.php
include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();
$sql4 = "SELECT * FROM users WHERE id='".$_SESSION['user_id']."'";
$rs4 = mysql_query($sql4);
$row4 = mysql_fetch_array($rs4);
if(isPolice()){
	header('location:police.php?sc='.$_REQUEST['sc']);	
}
else if($row4['clientId']!=0){
	header('location:police.php?sc='.$_REQUEST['sc']);	
}
else if($_SESSION['user_level'] < 2){
	header('location:index.php');	
}
$survey_id = $_REQUEST['sid'];
$currency=getCurrency();
echo menu();


$col1 = 85;
$col2 = 200;
$history_item = 3;
$total=0;

if(trim($survey_id)!==''){
	$sql1 = "SELECT * FROM survey WHERE id= '$survey_id'";
	$rs1 = mysql_query($sql1);
	$row1 = mysql_fetch_array($rs1);
	$id=$row1['service_req_id'];
}

$sql = "SELECT * FROM service_req WHERE id = '$id'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$st ='';
if($_SESSION['user_level'] < POWER_LEVEL){
	$st='readonly="readonly"';	
}
$lic=$row['a_number'];
$policy=$row['pol'];

$sql14 = "SELECT * FROM service_req_extra WHERE sc_id = '$id'";
$rs14 = mysql_query($sql14);
$row14 = mysql_fetch_array($rs14);

$sql2 = "SELECT COUNT(*) as t FROM VW_VEHICLE WHERE VehStatus='A'";
$rs2= sqlsrv_query($conn,$sql2);
$row2 = sqlsrv_fetch_array($rs2);

$claimNo=$row['claimNo'];
$sql13 = "SELECT * FROM VW_CLAIMS WHERE ClaimNo='$claimNo'";
$rs13= sqlsrv_query($conn,$sql13);
$row13 = sqlsrv_fetch_array($rs13);

?>


<form name="survey" enctype="multipart/form-data" action="rec_survey_parts.php?sid=<?php echo $survey_id;?>" method="post">
	
	<table width="1200" cellspacing="0" >
    	<tr>
        	<td colspan="10" align="center"><h8 style="color:#FFF">Survey Parts List # <?php  if(trim($survey_id)!==''){	echo str_pad($survey_id,5,'0',STR_PAD_LEFT);} else echo 'New';?></h8></td>
      </tr>
        <tr><td colspan="10" align="right" style="color:#fff">Total Insured Vehicles: <?php echo $row2['t'];?></td></tr>
         <tr>
        	<td class="top-left-child" width="15%">&nbsp;</td>
            <td class="top-center-child" width="15%">&nbsp;</td>
            <td class="top-right-child" colspan="8">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="15%">Parts Number</td>
            <td class="middle-none-child" width="15%">Parts Description</td>
            <td class="middle-none-child" width="10%">Price Afl. Audatex</td>
            <td class="middle-none-child" width="25%">Parts Supplier</td> 
            <td class="middle-none-child" width="10%">Price Afl. Loc</td> 
            <td class="middle-none-child" width="10%">Day Value Parts</td> 
            <td class="middle-right-child" colspan="4"></td>
        </tr>
        <?php
		
			$dbi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			$sql5="SELECT * FROM `survey_parts` WHERE `survey_id`=".$survey_id;
			$rs5=$dbi->query($sql5);
			$ltotal=0;
			$dtotal=0;
			
			$sql2 = "SELECT * FROM VW_VEHICLE WHERE LicPlateNo = '$lic' AND PolicyNo LIKE '$policy' ORDER BY 
				CASE	WHEN VehStatus='A' THEN 1
						WHEN VehStatus='L' THEN 2
						WHEN VehStatus='C' THEN 3
				End
				, Date_Renewal DESC, PolicyNo DESC";
			$params = array();
			$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$rs2 = sqlsrv_query($conn,$sql2,$params,$options);
			$row2 = sqlsrv_fetch_array($rs2);
			
			while($row5=$rs5->fetch_assoc()){
				if(trim($row5['transportation'])!== ''){
					$sql6="SELECT * FROM `parts_supplier` WHERE `id`=".$row5['parts_supplier_id'];
					$rs6=$dbi->query($sql6);
					if($row6=$rs6->fetch_assoc()){
						$cname=$row6['name'];
					}
					$pprice="USD. ".number_format($row5['price_us'],2);
					$lprice=$currency.number_format($row5['price_ov'],2);
					$total+=$row5['price'];
					$ltotal+=$row5['price_ov'];
					$dvalue=number_format(dayValue($row2['YearMake'],$row5['price_ov'],$row2['VehUse']),2);				
					$dtotal+=dayValue($row2['YearMake'],$row5['price_ov'],$row2['VehUse']);
				}
				else{
					$cname='';
					$pprice='';	
					$ltotal='';
				}
				
				/*
				echo number_format($row2['VehicleValue'],2).' / '.number_format(dayValue($row2['YearMake'],$row2['VehicleValue'],$row2['VehUse']),2);
				*/
				
				$to='<select name="ls[]"  style="background-color:#FAD090">';
				$sql6="SELECT * FROM `parts_supplier` WHERE active=1";
				$rs6=$dbi->query($sql6);
				while($row6=$rs6->fetch_assoc()){
					if($row5['parts_supplier_id']==$row6['id']){
						$to .= '<option value="'.$row6['id'].'" selected="selected">'.$row6['name'].'</option>';
					}
					else{
						$to .= '<option value="'.$row6['id'].'">'.$row6['name'].'</option>';	
					}
				}
				$to.='</select>';
				echo '
					<input type="hidden" name="li[]" value="'.$row5['line_id'].'"/>
					<tr>
						<td class="middle-left-child">'.$row5['part_number'].'</td>
           				<td class="middle-none-child">'.$row5['description'].'</td>
						<td class="middle-none-child">'.$pprice.'</td>
						<td class="middle-none-child">'.$to.'</td>
						<td class="middle-none-child">'.$lprice.'</td>
						<td class="middle-none-child">'.$dvalue.'</td>
						<td class="middle-right-child" colspan="4"></td>
					</tr>
				';	
			}
		
		?>
        <tr>
        	<td class="middle-left-child" width="15%"></td>
            <td class="middle-none-child" width="15%"></td>
            <td class="middle-none-child" width="10%"></td>
            <td class="middle-none-child" width="25%"></td> 
            <td class="middle-none-child" width="10%"><?php echo number_format($ltotal,2);?></td> 
            <td class="middle-none-child" width="10%"><?php echo number_format($dtotal,2);?></td> 
            <td class="middle-right-child" colspan="4"></td>
        </tr>
        <tr>
        	<td class="middle-child" colspan="10">&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="15%">Choose Supplier:</td>
            <td class="middle-none-child" width="15%">
            <select name="supplier" id="supplier" style="background-color:#FAD090">
            <option value=""></option>
            <?php
				$sql5="SELECT * FROM `parts_supplier` WHERE active=1";
				$rs5=$dbi->query($sql5);
				while($row5=$rs5->fetch_assoc()){
					echo '<option value="'.$row5['id'].'">'.$row5['name'].'</option>';	
				}
			?>
            </select>
            </td>
            <td class="middle-right-child" colspan="8"><?php
            	echo number_format($total,2);
			?></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="15%">Choose Transport:</td>
            <td class="middle-none-child" width="15%">
            <select name="transport" id="transport" style="background-color:#FAD090">
            <option value="s">Sea</option>
            <option value="a">Air</option>
            </select>
            </td>
            <td class="middle-right-child" colspan="8"></td>
        </tr>
        <tr>
        	<td class="bottom-child" colspan="10"><input type="submit" name="save" value="Submit" style="width:80px"/>
            </td>
        </tr>
  </table>
</form>
<?php $dbi->close(); ?>
</body>
</html>