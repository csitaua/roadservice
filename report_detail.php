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
	
	$status = $_REQUEST['status'];
	$job = $_REQUEST['job'];
	$jobs_group = $_REQUEST['jobs_group'];
	if(isset($_REQUEST['nsh'])){
		$nsh = 1;	
	}
	else{
		$nsh = 0;
	}
	$filter = '';
	$filter = $filter.' AND over_time = '.$nsh;
	if($status != 0){
		$filter = $filter.' AND status = '.$status;	
	} 
	if(trim($_REQUEST['cid']) !== ''){
		$filter = $filter.' AND ClientNo= '.(trim($_REQUEST['cid']));	
	}
	if($job != 0){
		$filter = $filter.' AND job = '.$job;		
	}
	if($jobs_group != 0 && $job ==0){
		$sql3 = "SELECT * FROM jobs WHERE jobs_group_id = '$jobs_group' ";
		$rs3 = mysql_query($sql3);
		$filter = $filter.' AND (';
		$first_line=1;
		while($row3 = mysql_fetch_array($rs3)){
			if($first_line){
				$first_line = 0;
				$filter = $filter.'job = '.$row3['id'];	
			}
			else{
				$filter = $filter.' OR job = '.$row3['id'];
			}
		}
		$filter = $filter.')';
	}
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
	$pdf->Cell(50,5,$sdate.' to '.$edate.' Road Service Detail Report');
	$pdf->Ln(20);
	$aid = $_REQUEST['aid'];
	if($aid==0){ //choose all
		$sql = "SELECT * FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND `delete` = 0".$filter." order by STR_TO_DATE( `opendt` , '%m-%d-%Y %k:%i' ) DESC, id DESC";
	}
	else{
		$sql = "SELECT * FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND `attendee_id` = '$aid' AND `delete` = 0".$filter." order by STR_TO_DATE( `opendt` , '%m-%d-%Y %k:%i' ) DESC, id DESC";
	}
	$n = 1;
	$rs = mysql_query($sql);
	$pdf->SetFont('helvetica', 'B', 11);
	$total = 0;
	
	
	$pdf->Cell(8,5,'');
	$pdf->cell(20,5,'Id');
	$pdf->cell(35,5,'License Plate');
	$pdf->cell(25,5,'Attendee');
	$pdf->cell(85,5,'Location');
	$pdf->Cell(20,5,'Receipt');
	$pdf->cell(30,5,'Job');
	$pdf->cell(25,5,'PO Number');
	$pdf->cell(40,5,'Time');
	$pdf->cell(30,5,'Charged');
	$pdf->cell(0,5,'Status');
	$pdf->Ln(6);
	
	$pdf->SetFont('helvetica', '', 11);
	
	while($row = mysql_fetch_array($rs)){ //loop throug service request
		$pdf->Cell(8,5,$n);
		$n++;
		$pdf->cell(20,5,str_pad($row['id'],5,'0',STR_PAD_LEFT));
		$pdf->cell(35,5,$row['a_number']);
		
		$atid = $row['attendee_id'];
		$sql2 = "SELECT * FROM attendee WHERE id='$atid'";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->cell(25,5,$row2['s_name']);
		$y = $pdf->gety();
		$x = $pdf->getx();
		$pdf->multicell(85,5,$row['location'],0,'L',false,2);
		$ya = $pdf->gety();
		$pdf->sety($y);
		$pdf->setx($x+85);
		$pdf->Cell(20,5,$row['receipt']);
		$jid = $row['job'];
		$sql2 = "SELECT * FROM jobs WHERE id='$jid'";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->cell(30,5,$row2['description']);
		$pdf->Cell(25,5,$row['po_number']);
		$pdf->cell(40,5,$row['opendt']);
		$pdf->cell(30,5,'Afl. '.number_format($row['charged'],2));
		$total = $total + $row['charged'];
		$jid = $row['status'];
		$sql2 = "SELECT * FROM status WHERE id='$jid'";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->cell(0,5,$row2['status']);
		
		
		$pdf->sety($ya);
	}
	$pdf->Ln(5);
	$pdf->SetFont('helvetica', 'B', 11);
	$pdf->Cell(258,5,'');
	$pdf->Cell(30,5,'Total');
	$pdf->cell(30,5,'Afl. '.number_format($total,2));
	
	$pdf->Output('DetailReport.pdf', 'D');	
}

echo menu();
$col1 = 120;
$col2 = 275;

?>

<form name="m_report" action="report_detail.php" method="post">
	<table width="900" cellspacing="0">
    	<tr>
        	<td colspan="3" align="center" style="border:0;color:#148540"><div class="rounded_h"><h3>Detailed Report by date range and attendee</h3></div></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
        	<td class="top-left-child" width="<?php echo $col1;?>">Starting Date:</td>
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
        	<td class="middle-left-child" width="<?php echo $col1;?>">End Date:</td>
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
        <td class="middle-left-child" width="<?php echo $col1;?>">Client ID:</td>
        <td class="middle-right-child" width="<?php echo $col2;?>">
        <input name="cid" style="background-color:#FAD090" size="15"/>
        <td>&nbsp;</td>
  	</td><tr>
	<tr>
        <td class="middle-left-child" width="<?php echo $col1;?>">Attendee:</td>
        <td class="middle-right-child" width="<?php echo $col2;?>"><select name="aid" style="background-color:#FAD090">
        	<option value="0">All</option>
            <?php
				$sql = "SELECT * FROM attendee WHERE id != 10 order by s_name ";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					echo '<option value="'.$row['id'].'">'.$row['s_name'].'</option>';	
				}
			?>
        </select>
        <td>&nbsp;</td>
  	</td><tr>
        <td class="middle-left-child" width="<?php echo $col1;?>">Status:</td>
        <td class="middle-right-child" width="<?php echo $col2;?>"><select name="status" style="background-color:#FAD090">
           <option value="0">All</option>
            <?php
				$sql = "SELECT * FROM status ";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					echo '<option value="'.$row['id'].'">'.$row['status'].'</option>';	
				}
			?>
        </select>
        <td>&nbsp;</td>
  	</td>
    </td><tr>
        <td class="middle-left-child" width="<?php echo $col1;?>">Jobs Group:</td>
        <td class="middle-right-child" width="<?php echo $col2;?>"><select name="jobs_group" id="jobs_group" style="background-color:#FAD090" onchange="updatejobs(this.selectedIndex)">
           <option value="0">All</option>
            <?php
				$sql = "SELECT * FROM jobs_group order by description ";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					echo '<option value="'.$row['id'].'">'.$row['description'].'</option>';	
				}
			?>
        </select>
        <td>&nbsp;</td>
  	</td>
    </td><tr>
        <td class="middle-left-child" width="<?php echo $col1;?>">Jobs:</td>
        <td class="middle-right-child" width="<?php echo $col2;?>"><select name="job" id="jobs" style="background-color:#FAD090">
           <option value="0">All</option>
            <?php
				$sql = "SELECT * FROM jobs ";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					echo '<option value="'.$row['id'].'">'.$row['description'].'</option>';	
				}
			?>
        </select>
        <td>&nbsp;</td>
  	</td>
      </td><tr>
        <td class="middle-left-child" width="<?php echo $col1;?>">NSH:</td>
        <td class="middle-right-child" width="<?php echo $col2;?>"><input type="checkbox" name="nsh" <?php if($nsh==1){echo 'checked="checked"';} ?> />
        <td>&nbsp;</td>
  	</td>
    
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