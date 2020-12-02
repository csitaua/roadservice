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
	require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');
	$sdate = $_REQUEST['sdate'];
	$edate = $_REQUEST['edate'];
	$col1=45;
	$col2=15;
	$col3 = 16;
	
	$pdf = new TCPDF('L', 'mm', 'letter', true, 'UTF-8', false);
	
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nagico Road Service');
	$pdf->SetTitle('Monthly Summary');
	$pdf->SetSubject('Monthly Summary');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->setAutoPageBreak(false,0);
	$pdf->SetFont('helvetica', 'B', 18);
	
	$pdf->AddPage();
	
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
		
	$pdf->Cell(80,5,'');
	$pdf->Cell(50,5,$sdate.' to '.$edate.' Road Service Report');
	$pdf->Ln(20);
	
	
	//*********************************Service Request Number All*******************************
	$pdf->SetFont('helvetica', 'B', 14);
	$pdf->Cell(0,8,'Service Request Number All',0,1,'C');
	$pdf->SetFont('helvetica', '', 8);
	
	//Column description
	$pdf->Cell($col1,5,'');
	$sql = "SELECT * FROM jobs";
	$rs = mysql_query($sql);
	while($row =  mysql_fetch_array($rs)){
		$pdf->Cell($col3,5,substr($row['description'],0,10),0,0,'C');
	}
	$pdf->Cell($col3,5,'Total',0,0,'C');
	
	$pdf->Ln(7);
	
	//Loop through attendees
	$sql = "SELECT * FROM attendee";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)){
		$aid = $row['id'];
		$sql2 = 	"SELECT COUNT(id) as num FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col1,5,$row['f_name'].':');
		
		//Loop through Jobs for each attendee
		$sql3 = "SELECT * FROM jobs";
		$rs3 = mysql_query($sql3);
		while($row3 =  mysql_fetch_array($rs3)){
			$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$pdf->Cell($col3,5,$row4['num'],0,0,'C');
		}		
		$pdf->Cell($col3,5,$row2['num'],'L',1,'C');
	}
	
	
	//Get total for each job type
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell($col1,5,'Total:',0,0,'R');
	$sql = "SELECT * FROM jobs";
	$rs = mysql_query($sql);
	while($row =  mysql_fetch_array($rs)){
		$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col3,5,$row2['num'],'T',0,'C');
	}
	
	//Get total for all jobs in month and year
	$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND `delete` = 0";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$pdf->Cell($col3,5,$row2['num'],'TL',0,'C');
	
	$pdf->Ln(15);
	//*********************************END Service Request Number All*******************************
	
	//*********************************Service Request Charges**************************************
	$pdf->SetFont('helvetica', 'B', 14);
	$pdf->Cell(0,8,'Service Request Charges All',0,1,'C');
	$pdf->SetFont('helvetica', '', 8);
	
	//Column description
	$pdf->Cell($col1,5,'');
	$sql = "SELECT * FROM jobs";
	$rs = mysql_query($sql);
	while($row =  mysql_fetch_array($rs)){
		$pdf->Cell($col3,5,substr($row['description'],0,10),0,0,'C');
	}
	$pdf->Cell($col3,5,'Total',0,0,'C');
	
	$pdf->Ln(7);
	
	//Loop through attendees
	$sql = "SELECT * FROM attendee";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)){
		$aid = $row['id'];
		$sql2 = 	"SELECT SUM(charged) as num FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col1,5,$row['f_name'].':');
		
		//Loop through Jobs for each attendee
		$sql3 = "SELECT * FROM jobs";
		$rs3 = mysql_query($sql3);
		while($row3 =  mysql_fetch_array($rs3)){
			$sql4 = "SELECT SUM(charged) as num FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$pdf->Cell($col3,5,number_format($row4['num'],2),0,0,'C');
		}		
		$pdf->Cell($col3,5,number_format($row2['num'],2),'L',1,'C');
	}
	
	
	//Get total for each job type
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell($col1,5,'Total:',0,0,'R');
	$sql = "SELECT * FROM jobs";
	$rs = mysql_query($sql);
	while($row =  mysql_fetch_array($rs)){
		$sql2 = "SELECT SUM(charged) as num FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col3,5,number_format($row2['num'],2),'T',0,'C');
	}
	
	//Get total for all jobs in month and year
	$sql2 = "SELECT SUM(charged) as num FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND `delete` = 0";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$pdf->Cell($col3,5,number_format($row2['num'],2),'TL',0,'C');
	
	$pdf->Ln(15);
	
	//*********************************Service Request Charges**************************************
	
	$pdf->Output('Date Range Report.pdf', 'D');	
}

echo menu();
$col1 = 120;
$col2 = 275;

?>

<form name="m_report" action="summary_range.php" method="post">
	<table width="900">
    	<tr>
        	<td colspan="3" align="center" style="border:0;color:#148540"><h3>Summary Report by date range</h3></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">Starting Date:</td>
            <td width="<?php echo $col2;?>">
            <input type="text" id="sdate" name="sdate" readonly="readonly"/>
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
        	<td width="<?php echo $col1;?>">End Date:</td>
            <td width="<?php echo $col2;?>">
             <input type="text" id="edate" name="edate" readonly="readonly"/>
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
            <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
            <td>&nbsp;</td>
        </tr>
    </table>
</form>

</body>
</html>