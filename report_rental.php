<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

if($_SESSION['user_level'] < POWER_LEVEL && !checkView()) {
header("Location: index.php");
exit();
}

if(strcmp($_REQUEST['submit'],'Submit')==0){
	require_once('tcpdf/tcpdf.php');
	$sdate = $_REQUEST['sdate'];
	$edate = $_REQUEST['edate'];
	$col1=50;
	$col2=15;
	$col3=18;
	
	$billto = $_REQUEST['billto'];
	
	$filter = "STR_TO_DATE(`time_in`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`time_in`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND bill_to='$billto'";
	
	
	
	$pagelayout = array(216, 355.6); //legal
	$pdf = new TCPDF('L', 'mm', $pagelayout, true, 'UTF-8', false);
	
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nagico Road Service');
	$pdf->SetTitle('Detail Report');
	$pdf->SetSubject('Detail Report');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->setAutoPageBreak(true,10);
	$pdf->SetFont('helvetica', 'B', 18);
	
	$pdf->AddPage();
	
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
		
	$pdf->Cell(80,5,'');
	$pdf->Cell(50,5,$sdate.' to '.$edate.' Road Service Rental Report Report');
	$pdf->Ln(20);
	$sql = "SELECT * FROM `rental` WHERE ".$filter." order by STR_TO_DATE( `time_in` , '%m-%d-%Y %k:%i' ) DESC, id DESC";

	$n = 1;
	$rs = mysql_query($sql);
	$pdf->SetFont('helvetica', 'B', 11);
	$total = 0;
	
	
	$pdf->Cell(8,5,'');
	$pdf->cell(20,5,'Id');
	$pdf->cell(65,5,'Name');
	$pdf->cell(35,5,'Date Out');
	$pdf->cell(35,5,'Date In');
	$pdf->Cell(40,5,'ClaimNo');
	$pdf->cell(60,5,'Requested By');
	$pdf->cell(15,5,'Days');
	$pdf->cell(20,5,'Rate');
	$pdf->cell(17,5,'BBO');
	$pdf->cell(25,5,'Total');
	$pdf->Ln(6);
	
	$pdf->SetFont('helvetica', '', 11);
	
	while($row = mysql_fetch_array($rs)){ //loop throug rentals request
		$time_out = $row['time_out'];
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
		if($interval->h==0){
			$total_charge = $row['rate']*$interval->days;
			$days_rent = $interval->days;
		}
		else{
			$total_charge = $row['rate']*($interval->days+1);
			$days_rent = ($interval->days+1);
		}
		
		
		$pdf->Cell(8,5,$n);
		$n++;
		$pdf->cell(20,5,str_pad($row['id'],5,'0',STR_PAD_LEFT));
		
		$drv = $row['extra_drv'];
		$sql2 = "SELECT * FROM drivers_license WHERE id = '$drv'";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$y = $pdf->gety();
		$x = $pdf->getx();
		$pdf->multicell(65,5,$row2['firstName'].' '.$row2['lastName'],0,'L',false,2);
		$ya = $pdf->gety();
		$pdf->sety($y);
		$pdf->setx($x+65);
		
		$pdf->cell(35,5,$row['time_out']);
		$pdf->cell(35,5,$row['time_in']);

		
		$pdf->Cell(40,5,$row['claimNo']);
		$req = $row['requested_by'];
		$sql2 = "SELECT * FROM rental_request WHERE id='$req'";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->cell(60,5,$row2['name']);
		$pdf->Cell(15,5,$days_rent);
		$pdf->Cell(20,5,number_format($row['rate'],2));
		$pdf->cell(17,5,number_format($total_charge*0.015,2));
		$pdf->cell(25,5,'Afl. '.number_format(1.015*$total_charge,2));
		
		$total = $total + 1.015*$total_charge;		
		
		$pdf->sety($ya);
	}
	$pdf->Ln(5);
	$pdf->SetFont('helvetica', 'B', 11);
	$pdf->Cell(285,5,'');
	$pdf->Cell(30,5,'Total');
	$pdf->cell(30,5,'Afl. '.number_format($total,2));
	
	$pdf->Output('DetailReport.pdf', 'D');	
}

echo menu();
$col1 = 175;
$col2 = 275;

?>

<form name="m_report" action="report_rental.php" method="post">
	<table width="900" cellspacing="0">
    	<tr>
        	<td colspan="3" align="center" style="border:0;color:#148540"><div class="rounded_h"><h3>Detailed Report by date range and attendee</h3></div></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
        	<td class="top-left-child" width="<?php echo $col1;?>">Starting Date Returned:</td>
            <td class="top-right-child" width="<?php echo $col2;?>">
            <input type="text" id="sdate" name="sdate" readonly/>
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
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">End Date Returned:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
             <input type="text" id="edate" name="edate" readonly/>
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
         <td>&nbsp;</td>
     </tr>
     <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Bill To:</td>
            <td class="middle-right-child" width="<?php echo $col2;?>">
             <select name="billto">
             	<option value="1">Nagico Claims</option>
                <option value="2">Driver</option>
             </select>
   	</td><td>&nbsp;</td></tr>
  	<tr>
     	<td class="bottom-child"  colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
      	<td>&nbsp;</td>
 	</tr>
    </table>
</form>
<script language="javascript" type="text/javascript">
	var jobs_group_list=document.m_report.jobs_group
	var jobs_list=document.m_report.jobs
	 
	var jobs=new Array()
	jobs[0]=[<?php 
		$sql2 = "SELECT * FROM jobs order by description";
		$rs2 = mysql_query($sql2);
		$first_line = 1;
		while($row2 = mysql_fetch_array($rs2)){
			if($first_line){
				echo '"'.$row2['description'].'|'.$row2['id'].'"';
				$first_line = 0;	
			}
			else{
				echo ', "'.$row2['description'].'|'.$row2['id'].'"';
			}
		}
	?>];
	<?php
		$sql2 = "SELECT * FROM jobs_group order by id";
		$rs2 = mysql_query($sql2);
		while($row2 = mysql_fetch_array($rs2)){
			$jobs_group_id = $row2['id'];
			$sql3 = "SELECT * FROM jobs WHERE jobs_group_id='$jobs_group_id'";
			$first_line = 1;
			$end = 0;
			$rs3 = mysql_query($sql3);
			while($row3 = mysql_fetch_array($rs3)){
				$end=1;
				if($first_line){
					echo 'jobs['.$jobs_group_id.']=["All|0", "'.$row3['description'].'|'.$row3['id'].'"';
					$first_line = 0;
				}
				else{
					echo ', "'.$row3['description'].'|'.$row3['id'].'"';
				}
			}	
			if($end) {echo '];'."\n";	}	
		}
	?>	
	function updatejobs(selectedjobgroup){
	jobs_list.options.length=0
	var jobs_list_id = document.getElementById('jobs_group').options[selectedjobgroup].value;
	for (i=0; i<jobs[jobs_list_id].length; i++)
		jobs_list.options[jobs_list.options.length]=new Option(jobs[jobs_list_id][i].split("|")[0], jobs[jobs_list_id][i].split("|")[1])
	}

 
</script> 
</body>
</html>