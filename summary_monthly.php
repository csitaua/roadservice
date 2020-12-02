<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();
set_time_limit(1200);
if($_SESSION['user_level'] < POWER_LEVEL && !checkView()) {
header("Location: index.php");
exit();
}

if(strcmp($_REQUEST['submit'],'Submit')==0){
	//require_once('tcpdf/config/lang/eng.php');
	require_once('tcpdf/tcpdf.php');
	$year = $_REQUEST['year'];
	$month = $_REQUEST['month'];
	$col1=45;
	$col2=20;
	$col3 = 21;
	
	$pagelayout = array(594, 841); //A1
	$pdf = new TCPDF('L', 'mm', $pagelayout, true, 'UTF-8', false);
	
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nagico Road Service');
	$pdf->SetTitle('Monthly Summary');
	$pdf->SetSubject('Monthly Summary');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->setAutoPageBreak(false,0);
	$pdf->SetFont('helvetica', 'B', 22);
	
	$pdf->AddPage();
	
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
		
	$pdf->Cell(80,5,'');
	$pdf->Cell(50,5,monthName($month).' '.$year.' Road Service Monthly Report');
	$pdf->Ln(20);
	
	
	//*********************************Service Request Number All*******************************
	$pdf->SetFont('helvetica', 'B', 14);
	$pdf->Cell(0,8,'Service Request Number All Excluding Towing',0,1,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	//Column description
	$pdf->Cell($col1,5,'');
	//$sql = "SELECT * FROM jobs WHERE jobs_group_id != 5 AND jobs_group_id != 2 AND jobs_group_id != 3 AND id!=11 AND id!=30 AND id!=21 AND id!=24 AND id!=26 AND id!=45 AND id!=35 AND id!=34 AND id!=21 AND id!=44 AND id!=38 AND id!=18 AND id!=28 AND id!=8 AND id!=31 AND id!=15 by sort_order";
	$sql = "SELECT * FROM jobs WHERE jobs_group_id != 5 AND jobs_group_id != 2 AND jobs_group_id != 3 AND id!=11 AND id!=30 AND id!=21 AND id!=24 AND id!=26 AND id!=45 AND id!=35 AND id!=34 AND id!=21 AND id!=44 AND id!=38 AND id!=18 AND id!=28 AND id!=8 AND id!=31 AND id!=15 AND id!=46 AND id!=55 AND id!=73 AND id!=10 AND id!=74 AND id!=37 AND id NOT IN (61, 60, 64, 21, 49, 48, 68, 24, 78, 62) order by sort_order";
	$rs = mysql_query($sql);
	while($row =  mysql_fetch_array($rs)){
		$pdf->Cell($col3,5,substr($row['description'],0,10),0,0,'C');
	}
	$pdf->Cell($col3,5,'Total',0,0,'C');
	$pdf->Cell($col3,5,'Total Year',0,0,'C');
	
	$pdf->Ln(7);
	
	//Loop through attendees
	$sql = "SELECT * FROM attendee WHERE active = 1";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)){
		$aid = $row['id'];
		$sql2 = 	"SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col1,5,$row['f_name'].':');
		
		//Loop through Jobs for each attendee
	$sql3 = "SELECT * FROM jobs WHERE jobs_group_id != 5 AND jobs_group_id != 2 AND jobs_group_id != 3 AND id!=11 AND id!=30 AND id!=21 AND id!=24 AND id!=26 AND id!=45 AND id!=35 AND id!=34 AND id!=21 AND id!=44 AND id!=38 AND id!=18 AND id!=28 AND id!=8 AND id!=31 AND id!=15 AND id!=46 AND id!=55 AND id!=73 AND id!=10 AND id!=74 AND id!=37 AND id NOT IN (61, 60, 64, 21, 49, 48, 68, 24, 78, 62) order by sort_order";
		$rs3 = mysql_query($sql3);
		$total = 0;
		$totaly=0;
		while($row3 =  mysql_fetch_array($rs3)){
			$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$pdf->Cell($col3,5,$row4['num'],0,0,'C');
			$total = $total + $row4['num'];
			
			$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))<='$month' AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$totaly = $totaly + $row4['num'];
		}		
		$pdf->Cell($col3,5,$total,'L',0,'C');
		$pdf->Cell($col3,5,$totaly,'L',1,'C');
	}
	
	
	//Get total for each job type
	$pdf->SetFont('helvetica', '', 12);
	$pdf->Cell($col1,5,'Total:',0,0,'R');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id != 5 AND jobs_group_id != 2 AND jobs_group_id != 3  AND id!=11 AND id!=30 AND id!=21 AND id!=24  AND id!=26 AND id!=45 AND id!=35 AND id!=34 AND id!=21 AND id!=44 AND id!=38  AND id!=18 AND id!=28 AND id!=8 AND id!=31 AND id!=15  AND id!=46 AND id!=55 AND id!=73 AND id!=10 AND id!=74 AND id!=37 AND id NOT IN (61, 60, 64, 21, 49, 48, 68, 24, 78, 62) order by sort_order";
	$rs = mysql_query($sql);
	$total = 0;
	$totaly=0;
	while($row =  mysql_fetch_array($rs)){
		$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col3,5,$row2['num'],'T',0,'C');
		$total = $total + $row2['num'];
		
		$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND  MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))<='$month' AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$totaly = $totaly + $row2['num'];
	}
	
	//Get total for all jobs in month and year
	//$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `delete` = 0";
	//$rs2 = mysql_query($sql2);
	//$row2 = mysql_fetch_array($rs2);
	//$pdf->Cell($col3,5,$row2['num'],'TL',0,'C');
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell($col3,5,$total,'TL',0,'C');
	$pdf->Cell($col3,5,$totaly,'TL',0,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	$pdf->Ln(15);
	//*********************************END Service Request Number All*******************************
	
	//*********************************Service Request Charges**************************************
	$pdf->SetFont('helvetica', 'B', 14);
	$pdf->Cell(0,8,'Service Request Charges All Excluding Towing',0,1,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	//Column description
	$pdf->Cell($col1,5,'');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id != 5 and jobs_group_id != 3 AND id!=41 AND id!=11 AND id!=10 AND id!=37 AND id!=40 AND id!=35  AND id!=26 AND id!=45 AND id!=35 AND id!=34 AND id!=21 AND id!=44 AND id!=38 AND id!=23 AND id!=18 AND id!=8 AND id!=28 AND id!=31 AND id!=15 AND id!=52 AND id!=51 AND id!=46 AND id!=43 AND id!=73 AND id!=10 AND id!=74 AND id!=37 AND id NOT IN (61, 60, 64, 21, 49, 48, 68, 24, 78, 62) order by sort_order";
	$rs = mysql_query($sql);
	while($row =  mysql_fetch_array($rs)){
		$pdf->Cell($col3,5,substr($row['description'],0,10),0,0,'C');
	}
	$pdf->Cell($col3,5,'Total',0,0,'C');
	$pdf->Cell($col3,5,'Total Year',0,0,'C');
	
	$pdf->Ln(7);
	
	//Loop through attendees
	$sql = "SELECT * FROM attendee WHERE active = 1";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)){
		$aid = $row['id'];
		$sql2 = 	"SELECT SUM(charged) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col1,5,$row['f_name'].':');
		
		//Loop through Jobs for each attendee
		$sql3 = "SELECT * FROM jobs WHERE jobs_group_id != 5 and jobs_group_id != 3 AND id!=41 AND id!=11 AND id!=10 AND id!=37 AND id!=40 AND id!=35  AND id!=26 AND id!=45 AND id!=35 AND id!=34 AND id!=21 AND id!=44 AND id!=38  AND id!=23 AND id!=18 AND id!=18 AND id!=8 AND id!=28 AND id!=31 AND id!=15 AND id!=52 AND id!=51 AND id!=46 AND id!=43 AND id!=73 AND id NOT IN (61, 60, 64, 21, 49, 48, 68, 24, 78, 62) AND id!=10 AND id!=74 AND id!=37 order by sort_order";
		$rs3 = mysql_query($sql3);
		$total = 0;
		$totaly = 0;
		while($row3 =  mysql_fetch_array($rs3)){
			$sql4 = "SELECT SUM(charged) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$pdf->Cell($col3,5,number_format($row4['num'],2),0,0,'C');
			$total = $total + $row4['num'];
			
			$sql4 = "SELECT SUM(charged) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))<='$month'  AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$totaly = $totaly + $row4['num'];
		}		
		$pdf->Cell($col3,5,number_format($total,2),'L',0,'C');
		$pdf->Cell($col3,5,number_format($totaly,2),'L',1,'C');
	}
	
	
	//Get total for each job type
	$pdf->SetFont('helvetica', '', 12);
	$pdf->Cell($col1,5,'Total:',0,0,'R');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id != 5 and jobs_group_id != 3 AND id!=41 AND id!=11 AND id!=10 AND id!=37 AND id!=40 AND id!=35  AND id!=26 AND id!=45 AND id!=35 AND id!=34 AND id!=21 AND id!=44 AND id!=38  AND id!=23 AND id!=18 AND id!=18 AND id!=8 AND id!=28 AND id!=31 AND id!=15 AND id!=52 AND id!=51 AND id!=46 AND id!=43 AND id!=73 AND id!=10 AND id!=74 AND id!=37 AND id NOT IN (61, 60, 64, 21, 49, 48, 68, 24, 78, 62) order by sort_order";
	$rs = mysql_query($sql);
	$total = 0;
	$totaly = 0;
	while($row =  mysql_fetch_array($rs)){
		$sql2 = "SELECT SUM(charged) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col3,5,number_format($row2['num'],2),'T',0,'C');
		$total = $total + $row2['num'];
		
		$sql2 = "SELECT SUM(charged) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))<='$month' AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$totaly = $totaly + $row2['num'];
	}
	
	//Get total for all jobs in month and year
	//$sql2 = "SELECT SUM(charged) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `delete` = 0";
	//$rs2 = mysql_query($sql2);
	//$row2 = mysql_fetch_array($rs2);
	//$pdf->Cell($col3,5,number_format($row2['num'],2),'TL',0,'C');
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell($col3,5,number_format($total,2),'TL',0,'C');
	$pdf->Cell($col3,5,number_format($totaly,2),'TL',0,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	$pdf->Ln(15);
	
	//***********************************Add new Page for Towing Only******************************
	
	$pdf->AddPage();
	
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
		
	$pdf->Cell(80,5,'');
	$pdf->Cell(50,5,monthName($month).' '.$year.' Road Service Monthly Report');
	$pdf->Ln(20);
	
	
	//*********************************Service Request Number All*******************************
	$pdf->SetFont('helvetica', 'B', 14);
	$pdf->Cell(0,8,'Service Request Number Towing',0,1,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	//Column description
	$pdf->Cell($col1,5,'');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id = 5 AND id!=36 AND id!=16 order by sort_order";
	$rs = mysql_query($sql);
	while($row =  mysql_fetch_array($rs)){
		$pdf->Cell($col3,5,substr($row['description'],0,10),0,0,'C');
	}
	$pdf->Cell($col3,5,'Total',0,0,'C');
	$pdf->Cell($col3,5,'Total Year',0,0,'C');
	
	$pdf->Ln(7);
	
	//Loop through attendees
	$sql = "SELECT * FROM attendee WHERE active = 1";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)){
		$aid = $row['id'];
		$sql2 = 	"SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col1,5,$row['f_name'].':');
		
		//Loop through Jobs for each attendee
		$sql3 = "SELECT * FROM jobs WHERE jobs_group_id = 5 AND id!=36 AND id!=16 order by sort_order";
		$rs3 = mysql_query($sql3);
		$total = 0;
		$totaly = 0;
		while($row3 =  mysql_fetch_array($rs3)){
			$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$pdf->Cell($col3,5,$row4['num'],0,0,'C');
			$total = $total + $row4['num'];
			
			$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))<='$month' AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$totaly = $totaly + $row4['num'];
		}		
		$pdf->Cell($col3,5,$total,'L',0,'C');
		$pdf->Cell($col3,5,$totaly,'L',1,'C');
	}
	
	
	//Get total for each job type
	$pdf->SetFont('helvetica', '', 12);
	$pdf->Cell($col1,5,'Total:',0,0,'R');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id = 5 AND id!=36 AND id!=16 order by sort_order";
	$rs = mysql_query($sql);
	$total = 0;
	$totaly = 0;
	while($row =  mysql_fetch_array($rs)){
		$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col3,5,$row2['num'],'T',0,'C');
		$total = $total + $row2['num'];
		
		$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))<='$month' AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$totaly = $totaly + $row2['num'];
	}
	
	//Get total for all jobs in month and year
	//$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `delete` = 0";
	//$rs2 = mysql_query($sql2);
	//$row2 = mysql_fetch_array($rs2);
	//$pdf->Cell($col3,5,$row2['num'],'TL',0,'C');
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell($col3,5,$total,'TL',0,'C');
	$pdf->Cell($col3,5,$totaly,'TL',0,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	$pdf->Ln(15);
	//*********************************END Service Request Number All*******************************
	
	//*********************************Service Request Charges**************************************
	$pdf->SetFont('helvetica', 'B', 14);
	$pdf->Cell(0,8,'Service Request Charges Towing',0,1,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	//Column description
	$pdf->Cell($col1,5,'');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id = 5 AND id!=36 AND id!=16 order by sort_order";
	$rs = mysql_query($sql);
	while($row =  mysql_fetch_array($rs)){
		$pdf->Cell($col3,5,substr($row['description'],0,10),0,0,'C');
	}
	$pdf->Cell($col3,5,'Total',0,0,'C');
	$pdf->Cell($col3,5,'Total Year',0,0,'C');
	
	$pdf->Ln(7);
	
	//Loop through attendees
	$sql = "SELECT * FROM attendee WHERE active = 1";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)){
		$aid = $row['id'];
		$sql2 = 	"SELECT SUM(charged) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col1,5,$row['f_name'].':');
		
		//Loop through Jobs for each attendee
		$sql3 = "SELECT * FROM jobs WHERE jobs_group_id = 5 AND id!=36 AND id!=16 order by sort_order";
		$rs3 = mysql_query($sql3);
		$total = 0;
		$totaly = 0;
		while($row3 =  mysql_fetch_array($rs3)){
			$sql4 = "SELECT SUM(charged) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$pdf->Cell($col3,5,number_format($row4['num'],2),0,0,'C');
			$total = $total + $row4['num'];
			
			$sql4 = "SELECT SUM(charged) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))<='$month' AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$totaly = $totaly + $row4['num'];
		}		
		$pdf->Cell($col3,5,number_format($total,2),'L',0,'C');
		$pdf->Cell($col3,5,number_format($totaly,2),'L',1,'C');
	}
	
	
	//Get total for each job type
	$pdf->SetFont('helvetica', '', 12);
	$pdf->Cell($col1,5,'Total:',0,0,'R');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id = 5 AND id!=36 AND id!=16 order by sort_order";
	$rs = mysql_query($sql);
	$total = 0;
	$totaly = 0;
	while($row =  mysql_fetch_array($rs)){
		$sql2 = "SELECT SUM(charged) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col3,5,number_format($row2['num'],2),'T',0,'C');
		$total = $total + $row2['num'];
		
		$sql2 = "SELECT SUM(charged) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))<='$month' AND job = '$row[id]' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$totaly = $totaly + $row2['num'];
	}
	
	//Get total for all jobs in month and year
	//$sql2 = "SELECT SUM(charged) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `delete` = 0";
	//$rs2 = mysql_query($sql2);
	//$row2 = mysql_fetch_array($rs2);
	//$pdf->Cell($col3,5,number_format($row2['num'],2),'TL',0,'C');
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell($col3,5,number_format($total,2),'TL',0,'C');
	$pdf->Cell($col3,5,number_format($totaly,2),'TL',0,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	$pdf->Ln(15);
	
	//***********************************Add new Page for Claims Related Calls******************************
	
	$pdf->AddPage();
	
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
		
	$pdf->Cell(80,5,'');
	$pdf->Cell(50,5,monthName($month).' '.$year.' Road Service Monthly Report');
	$pdf->Ln(20);
	
	
	//*********************************Service Request Number All*******************************
	$pdf->SetFont('helvetica', 'B', 14);
	$pdf->Cell(0,8,'Service Request Number Claims',0,1,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	//Column description
	$pdf->Cell($col1,5,'');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id = 3 OR jobs_group_id = 7 order by sort_order";
	$rs = mysql_query($sql);
	while($row =  mysql_fetch_array($rs)){
		$pdf->Cell($col3,5,substr($row['description'],0,10),0,0,'C');
	}
	$pdf->Cell($col3,5,'Total',0,0,'C');
	$pdf->Cell($col3+10,5,'',0,0,'C');
	$pdf->Cell($col3+10,5,'Total Nagico Acc',0,0,'C');
	$pdf->Cell($col3+10,5,'Total Nagico Acc Y',0,0,'C');
	$pdf->Cell($col3+10,5,'',0,0,'C');
	$pdf->Cell($col3+10,5,'Total Nagico Inc',0,0,'C');
	$pdf->Cell($col3+10,5,'Total Nagico Inc Y',0,0,'C');
	
	$pdf->Ln(7);
	
	//Loop through attendees
	$sql = "SELECT * FROM attendee WHERE active = 1";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)){
		$aid = $row['id'];
		$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col1,5,$row['f_name'].':');
		
		//Loop through Jobs for each attendee
		$sql3 = "SELECT * FROM jobs WHERE jobs_group_id = 3 OR jobs_group_id = 7 order by sort_order";
		$rs3 = mysql_query($sql3);
		$total = 0;
		$total_acc=0;
		$total_accy=0;
		$total_inc=0;
		$total_incy=0;
		while($row3 =  mysql_fetch_array($rs3)){
			$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row3[id]' AND `attendee_id` = '$aid' AND `delete` = 0";
			$rs4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($rs4);
			$pdf->Cell($col3,5,$row4['num'],0,0,'C');
			$total = $total + $row4['num'];
			
		}		
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '7' AND `attendee_id` = '$aid' AND `delete` = 0 and master_sc='' AND ClientNo!=''";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$total_acc = $total_acc + $row4['num'];
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '7' AND `attendee_id` = '$aid' AND `delete` = 0 and master_sc='' AND ClientNo!=''";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$total_accy = $total_accy + $row4['num'];
		$pdf->Cell($col3,5,$total,'L',0,'C');
		
		$pdf->Cell($col3+10,5,'',0,0,'C');
		$pdf->Cell($col3+10,5,$total_acc,0,0,'C');
		$pdf->Cell($col3+10,5,$total_accy,'L',1,'C');
	}
	
	
	//Get total for each job type
	$pdf->SetFont('helvetica', '', 12);
	$pdf->Cell($col1,5,'Total:',0,0,'R');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id = 3 OR jobs_group_id = 7  order by sort_order";
	$rs = mysql_query($sql);
	$total = 0;
	$total_acc = 0;
	$total_accy = 0;
	while($row =  mysql_fetch_array($rs)){
		$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '$row[id]' AND `delete` = 0 ";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->Cell($col3,5,$row2['num'],'T',0,'C');
		$total = $total + $row2['num'];
		
	}
	
	$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '7' AND `delete` = 0 and master_sc='' AND ClientNo!=''";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$total_acc = $total_acc + $row4['num'];
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = '7' AND `delete` = 0 and master_sc='' AND ClientNo!=''";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$total_accy = $total_accy + $row4['num'];
	
	//Get total for all jobs in month and year
	//$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `delete` = 0";
	//$rs2 = mysql_query($sql2);
	//$row2 = mysql_fetch_array($rs2);
	//$pdf->Cell($col3,5,$row2['num'],'TL',0,'C');
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell($col3,5,$total,'TL',0,'C');
	
	$pdf->Cell($col3+10,5,'',0,'C');
	$pdf->Cell($col3+10,5,$total_acc,'T',0,'C');
	$pdf->Cell($col3+10,5,$total_accy,'TL',0,'C');
	$pdf->SetFont('helvetica', '+', 10);
	
	$pdf->Ln(15);
	//*********************************END Service Request Number All*******************************
	
	//*********************************Service Request Charges**************************************
	
	//*********************************Service Request Charges**************************************
	
	$pdf->SetFont('helvetica', 'B', 14);
	$pdf->Cell(0,8,'Service Request Accident',0,1,'C');
	$pdf->SetFont('helvetica', '', 10);
	
	$pdf->Cell($col1,5,'');
	$pdf->Cell($col3+10,5,'Acc Client RS AF',0,0,'C');
	$pdf->Cell($col3+10,5,'Acc Client RS NAF',0,0,'C');
	$pdf->Cell($col3+10,5,'Acc Client No RS',0,0,'C');
	$pdf->Cell($col3+10,5,'Acc Non Link',0,0,'C');
	$pdf->Cell($col3+10,5,'Acc Non ',0,0,'C');
	$pdf->Cell($col3,5,'Acc Non Non',0,0,'C');
	$pdf->Cell($col3,5,'Total',0,0,'C');
	
	$pdf->Cell($col3+10,5,'',0,0,'C');
	
	$pdf->Cell($col3+10,5,'Theft/Joy Client',0,0,'C');
	$pdf->Cell($col3+10,5,'Theft/Joy Non',0,0,'C');
	$pdf->Cell($col3,5,'Total',0,0,'C');
	

	$pdf->Ln(7);
	
	//Loop through attendees
	$sql = "SELECT * FROM attendee WHERE active = 1";
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)){
		$aid = $row['id'];
		//Acc Client RS AF per attendee
		
		$pdf->Cell($col1,5,$row['f_name'].':');
		$total = 0;
		$total2 = 0;
		
		$sql4 = 	"SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `attendee_id` = '$aid' AND ClientNo != '' AND `rspresent`=1 AND `delete` = 0 AND `id` in (SELECT `sc_id` FROM `service_req_extra` WHERE `status`='At Fault')";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$pdf->Cell($col3+10,5,$row4['num'],0,0,'C');
		$total = $total + $row4['num'];

		//Acc Client RS NAF per attendee
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND ClientNo != '' AND `attendee_id` = '$aid' AND `rspresent`=1 AND `delete` = 0 AND NOT(`id` in (SELECT `sc_id` FROM `service_req_extra` WHERE `status`='At Fault'))";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$pdf->Cell($col3+10,5,$row4['num'],0,0,'C');
		$total = $total + $row4['num'];
		
		//ACC Client No RS
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND ClientNo != '' AND `attendee_id` = '$aid' AND `delete` = 0 AND `rspresent`!=1";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$pdf->Cell($col3+10,5,$row4['num'],0,0,'C');
		$total = $total + $row4['num'];
		//ACC Non Linked			
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND Insured=0  AND master_sc > 0 AND `attendee_id` = '$aid' AND `delete` = 0 AND master_sc in(SELECT id FROM service_req WHERE ClientNo !='')";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$pdf->Cell($col3+10,5,$row4['num'],0,0,'C');
		$total = $total + $row4['num'];
			
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND Insured=0 AND master_sc < 1 AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$pdf->Cell($col3+10,5,$row4['num'],0,0,'C');
		$total = $total + $row4['num'];
		
		//Acc Non Non
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND Insured=0  AND master_sc > 0 AND `attendee_id` = '$aid' AND `delete` = 0 AND master_sc not in(SELECT id FROM service_req WHERE ClientNo !='')";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$pdf->Cell($col3,5,$row4['num'],0,0,'C');
		$total = $total + $row4['num'];
	
		$pdf->Cell($col3,5,$total,'L',0,'C');
		
		$pdf->Cell($col3+10,5,'',0,0,'C');
		
		//Theft Joyride client
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 18 AND ClientNo != '' AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$pdf->Cell($col3+10,5,$row4['num'],0,0,'C');
		$total2 = $total2 + $row4['num'];
		
		$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 18 AND ClientNo = '' AND `attendee_id` = '$aid' AND `delete` = 0";
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$pdf->Cell($col3+10,5,$row4['num'],0,0,'C');
		$total2 = $total2 + $row4['num'];
		
		$pdf->Cell($col3,5,$total2,'L',1,'C');
	}
	
	
	//Get total for each job type
	$pdf->SetFont('helvetica', '', 12);
	$pdf->Cell($col1,5,'Total:',0,0,'R');
	$sql = "SELECT * FROM jobs WHERE jobs_group_id = 3 order by sort_order";
	$rs = mysql_query($sql);
	$total = 0;
	
	//Acc Client RS at fault total
	$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND ClientNo != '' AND `delete` = 0 AND `rspresent`=1 AND `id` in (SELECT `sc_id` FROM `service_req_extra` WHERE `status`='At Fault')";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$pdf->Cell($col3+10,5,$row2['num'],'T',0,'C');
	$total = $total + $row2['num'];
	
	//Acc Client RS NAF total
	//$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND ClientNo != '' AND `delete` = 0 AND `rspresent`=1 AND `id` in (SELECT `sc_id` FROM `service_req_extra` WHERE `status`!='At Fault')";
	$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND ClientNo != '' AND `rspresent`=1 AND `delete` = 0 AND NOT(`id` in (SELECT `sc_id` FROM `service_req_extra` WHERE `status`='At Fault'))";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$pdf->Cell($col3+10,5,$row2['num'],'T',0,'C');
	$total = $total + $row2['num'];
		
	//Acc Client No RS total
	$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND ClientNo != '' AND `delete` = 0 AND `rspresent`!=1 ";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$pdf->Cell($col3+10,5,$row2['num'],'T',0,'C');
	$total = $total + $row2['num'];
		
	$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND Insured=0 AND master_sc > 0 AND `delete` = 0 AND master_sc in(SELECT id FROM service_req WHERE ClientNo !='')";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$pdf->Cell($col3+10,5,$row2['num'],'T',0,'C');
	$total = $total + $row2['num'];
		
	$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND Insured=0 AND master_sc = 0 AND `delete` = 0";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$pdf->Cell($col3+10,5,$row2['num'],'T',0,'C');
	$total = $total + $row2['num'];
	
	$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 7 AND Insured=0 AND master_sc > 0 AND `delete` = 0 AND master_sc not in(SELECT id FROM service_req WHERE ClientNo !='')";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$pdf->Cell($col3,5,$row2['num'],'T',0,'C');
	$total = $total + $row2['num'];
	
	
	//Get total for all jobs in month and year
	//$sql2 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND `delete` = 0";
	//$rs2 = mysql_query($sql2);
	//$row2 = mysql_fetch_array($rs2);
	//$pdf->Cell($col3,5,$row2['num'],'TL',0,'C');
	$pdf->Cell($col3,5,$total,'TL',0,'C');
	
	$pdf->Cell($col3+10,5,'',0,0,'C');
	
	$total2 = 0;
	$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 18 AND ClientNo != '' AND `delete` = 0";
	$rs4 = mysql_query($sql4);
	$row4 = mysql_fetch_array($rs4);
	$pdf->Cell($col3+10,5,$row4['num'],'T',0,'C');
	$total2 = $total2 + $row4['num'];
	
	$sql4 = "SELECT COUNT(id) as num FROM `service_req` WHERE MONTH (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$month' AND YEAR (STR_TO_DATE(`opendt`,'%m-%d-%Y'))='$year' AND job = 18 AND ClientNo = '' AND `delete` = 0";
	$rs4 = mysql_query($sql4);
	$row4 = mysql_fetch_array($rs4);
	$pdf->Cell($col3+10,5,$row4['num'],'T',0,'C');
	$total2 = $total2 + $row4['num'];
	
	$pdf->Cell($col3,5,$total2,'TL',0,'C');
	
	$pdf->Ln(15);
	
	//--------------------------- Add page Car wash and Rental -----------------------------------------------------------------------------------------
	
	$pdf->AddPage();
	$pdf->SetFont('helvetica', 'B', 22);
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
	$pdf->SetFont('helvetica', 'B', 16);	
	$pdf->Cell(80,5,'');
	$pdf->Cell(50,5,monthName($month).' '.$year.' Road Service Monthly Report');
	$pdf->Ln(20);
	$pdf->SetFont('helvetica', '', 12);
	$sql4 = "SELECT * FROM `rental_vehicle` WHERE 1 ORDER by make,model";
	$rs4 = mysql_query($sql4);
	
	$colh=75;
	$totalall=0;
	
	$pdf->Cell($colh,5,'');$pdf->Cell($col1,5,'Days');$pdf->Cell($col1,5,'Total Month');$pdf->Cell($col1,5,'Total Year');$pdf->Cell($col1,5,'MCW');$pdf->Cell($col1,5,'SCW');$pdf->Ln(7);
	while($row4 = mysql_fetch_array($rs4)){
		$sql5="SELECT * FROM `rental` WHERE `rental_vehicle_id`='".$row4['id']."' AND `delete_by`=0
		AND (( (MONTH (STR_TO_DATE(`time_out`,'%m-%d-%Y'))='$month' OR MONTH (STR_TO_DATE(`time_in`,'%m-%d-%Y'))='$month' ) 
		AND (YEAR (STR_TO_DATE(`time_out`,'%m-%d-%Y'))='$year') ) 
		OR 
		(`status`='Rented Out' AND (MONTH (STR_TO_DATE(`time_out`,'%m-%d-%Y'))<='$month') )) 
		AND `status`!='Reservation'";
		$rs5 = mysql_query($sql5);
		$days=0;
		$total=0;
		while($row5=mysql_fetch_array($rs5)){
			$time_out=$row5['time_out'];
			$time_in=$row5['time_in'];
			$rate=$row5['rate'];
			
			list($montho,$dayo,$yearo) = explode('-',substr($time_out,0,10));
			$mo=$month;
			$yo=$year;
			list($date,$time) = explode(' ',$time_out);
			if($montho!=$month){
				$montho=$month;
				$dayo=1;	
			}
			$date1 = new DateTime($yearo.'-'.$montho.'-'.$dayo.' '.$time);
			
			$temp_date = $year.'-'.$month.'-1';
		
			$date2 = new DateTime(date("Y-m-t H:i", strtotime($temp_date))); //Create date last date of the month
			if($year==date('Y') && $month==date('m')){ //check if date in current month if so takes today
				$date2 = new DateTime(date("Y-m-d H:i"));
			}
			
			if($row5['status']==='Rented Out'){
				//Make montho equal to current month
				$date1 = new DateTime($year.'-'.$month.'-1 '.$time);
			}
			
			if(strlen(trim($time_in))!=0){
				list($monthi,$dayi,$yeari) = explode('-',substr($time_in,0,10));
				list($date,$time) = explode(' ',$time_in);
				$date2 = new DateTime($yeari.'-'.$monthi.'-'.$dayi.' '.$time);
				if($monthi>$month){
					$temp_date2=	$year.'-'.$month.'-1 23:59';
					$date2 = new DateTime(date("Y-m-t H:i", strtotime($temp_date2)));	
				}
				if($montho>$mo){
					$date1= new DateTime($yeari.'-'.$monthi.'-01 00:00');	
				}
			}
			$interval = $date1->diff($date2);
			if($interval->h==0){
				$total=$total+$rate*$interval->days;
				$days=$days+$interval->days;
			}
			else{
				$total=$total+$rate*($interval->days+1);
				$days=$days+$interval->days+1;
			}
			
		}
		
			$pdf->Cell($colh,5,$row4['make'].' '.$row4['model'].' ('.$row4['licenseplate'].')');$pdf->Cell($col1,5,$days);$pdf->Cell($col1,5,number_format($total,2));$pdf->Cell($col1,5,'Total Year');$pdf->Cell($col1,5,'MCW');$pdf->Cell($col1,5,'SCW');$pdf->Ln(5);	
		
		$totalall+=$total;
	}
	
	$pdf->Cell($colh,5,'');$pdf->Cell($col1,5,'');$pdf->Cell($col1,5,number_format($totalall,2));$pdf->Cell($col1,5,'');$pdf->Cell($col1,5,'');$pdf->Cell($col1,5,'');$pdf->Ln(5);
	$pdf->Output(monthName($month).' '.$year.' Monthly Report.pdf', 'D');	
}

echo menu();
$col1 = 75;
$col2 = 275;

?>

<form name="m_report" action="summary_monthly.php" method="post">
	<table width="900">
    	<tr>
        	<td colspan="3" align="center" style="border:0;color:#148540"><h3>Montly Report</h3></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">Year:</td>
            <td width="<?php echo $col2;?>"><select name="year" style="background-color:#FAD090">
            <?php
				$fyear = 2011;
				$cyear = date('Y');
				while($fyear <=$cyear){
					if($fyear==$cyear){
						echo '<option selected="selected" value="'.$fyear.'">'.$fyear.'</option>';
					}
					else{
						echo '<option value="'.$fyear.'">'.$fyear.'</option>';
					}
					$fyear++;	
				}
			?>
            </select>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Month:</td>
            <td width="<?php echo $col2;?>"><select name="month" style="background-color:#FAD090">
            	<?php $cmonth = date('n')?>
            	<option <?php if($cmonth==1){echo 'selected="selected"';}?> value="1">January</option>
                <option <?php if($cmonth==2){echo 'selected="selected"';}?> value="2">February</option>
                <option <?php if($cmonth==3){echo 'selected="selected"';}?> value="3">March</option>
                <option <?php if($cmonth==4){echo 'selected="selected"';}?> value="4">April</option>
                <option <?php if($cmonth==5){echo 'selected="selected"';}?> value="5">May</option>
                <option <?php if($cmonth==6){echo 'selected="selected"';}?> value="6">June</option>
                <option <?php if($cmonth==7){echo 'selected="selected"';}?> value="7">July</option>
                <option <?php if($cmonth==8){echo 'selected="selected"';}?> value="8">August</option>
                <option <?php if($cmonth==9){echo 'selected="selected"';}?> value="9">September</option>
                <option <?php if($cmonth==10){echo 'selected="selected"';}?> value="10">October</option>
                <option <?php if($cmonth==11){echo 'selected="selected"';}?> value="11">November</option>
                <option <?php if($cmonth==12){echo 'selected="selected"';}?> value="12">December</option>
            </select></td>
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