<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//session_start();

	require_once('tcpdf/tcpdf.php');
	$id=$_REQUEST['id'];
	$height=6;

	$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nagico Road Service');
	$pdf->SetTitle('Adjuster Request '.$id);
	$pdf->SetSubject('Adjuster Request');

	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->setAutoPageBreak(true,10);

	$pdf->AddPage();
	$pdf->SetMargins(10,10,10,true);
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);



	$sql = "SELECT * FROM survey WHERE `id` =$id";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	$sid=$row['service_req_id'];
	if(trim($row['manu_date'])!==''){
		list($year,$month,$day)=explode('-',$row['manu_date']);
		$manu_date= new DateTime($year.'-'.$month.'-'.$day);
		$manu_date=date_format($manu_date,'j F, Y');
	}
	else{
		$manu_date='';
	}

	$sql2="SELECT * FROM service_req WHERE `id`=$sid";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);

	if(file_exists("rrimage/".$row2['id']) || file_exists("rrimage/".$row2['accident_link']) || file_exists("rrimage/".$row2['accident_link2']) || file_exists("rrimage/".$row2['accident_link3'])){
		$pic='Yes';
	}
	else{
		$pic='No';
	}

	$sql3="SELECT * FROM service_req_extra WHERE `sc_id`=$sid";
	$rs3 = mysql_query($sql3);
	$row3 = mysql_fetch_array($rs3);

	$sql5="SELECT * FROM `claimshandler` WHERE `id`=".$row['requested_by_id'];
	$rs5=mysql_query($sql5);
	$row5=mysql_fetch_array($rs5);

	$sql6="SELECT * FROM `adjuster` WHERE `id`=".$row['adjuster_id'];
	$rs6=mysql_query($sql6);
	$row6=mysql_fetch_array($rs6);

	$claimNo=$row2['claimNo'];
	$sql13 = "SELECT * FROM VW_CLAIMS WHERE ClaimNo='$claimNo'";
	$rs13= mssql_query($sql13);
	$row13 = mssql_fetch_array($rs13);

	$ddate= new datetime(substr($row13['Date_Loss'],0,10));
	$date_of_incident=date_format($ddate,"d M, Y");

	$sqlt="SELECT * FROM vehicle_impact WHERE id='".$row3['rep_impact']."'";
	$rst=mysql_query($sqlt);
	$rowt=mysql_fetch_array($rst);
	$type_impact=$rowt['description'];

	$sqlt="SELECT * FROM vehicle_damage WHERE id='".$row3['rep_damage']."'";
	$rst=mysql_query($sqlt);
	$rowt=mysql_fetch_array($rst);
	$type_dam=$rowt['description'];

	$sqlt="SELECT * FROM `bodyshop` WHERE `id`='".$row['bodyshop_id']."'";
	$rst=mysql_query($sqlt);
	$rowt=mysql_fetch_array($rst);
	$bodyshop=$rowt['name'];

	$request_by_name=$row5['name'];
	if($row2['insured']==1){
		$insurer='Nagico '.$_SESSION['country'];
	}
	else if($row2['insured_at']==1){
		$insurer='No Insurance';
	}
	else{
		$sqlt = "SELECT * FROM insurance_company WHERE id=".$row2['insured_at'];
		$rst=mysql_query($sqlt);
		$rowt=mysql_fetch_array($rst);
		$insurer=$rowt['name'];
	}
	$lic=$row2['a_number'];
	$policy=$row2['pol'];
	$sqlt = "SELECT * FROM VW_VEHICLE WHERE LicPlateNo = '$lic' AND PolicyNo LIKE '$policy' ORDER BY
				CASE	WHEN VehStatus='A' THEN 1
						WHEN VehStatus='L' THEN 2
						WHEN VehStatus='C' THEN 3
				End
				, Date_Renewal DESC, PolicyNo DESC";
	$rst= mssql_query($sqlt);

	if($rowt = mssql_fetch_array($rst)){
		$claimant=$rowt['Full_Name'];
		$yc=$rowt['YearMake'].' / '.$rowt['Color'];
		$bs=$rowt['BodyType'].' / '.$rowt['Seats'];
		$vuse=$rowt['VehUse'];
		$tdate = new datetime (substr($rowt['Date_Application'],0,10));
		$tdate = date_format($tdate,"d F, Y");
		$cat_value = number_format($rowt['VehicleValue'],2);
		$year_value = dayValue($rowt['YearMake'],$rowt['VehicleValue'],$rowt['VehUse']);
		if(trim($row['manu_date'])!=='' AND trim($row13['Date_Loss'])!==''){
			$day_value=dayValueA($row['manu_date'],substr($row13['Date_Loss'],0,10),$rowt['VehicleValue'],$rowt['VehUse']);
		}

	}
	else{
		$sqlt = "SELECT * FROM `non_client_extra` WHERE `id` = '".$lic."'";
		$rst = mysql_query($sqlt);
		$rowt=mysql_fetch_array($rst);
		$claimant=$rowt['fname'].' '.$rowt['lname'];
		$yc=$rowt['year'].' / '.$rowt['color'];
		$bs=$rowt['body_type'].' / '.$rowt['seats'];
		$cat_value = number_format($rowt['cat_value'],2);
		if($rowt['vehicle_use']==='private'){
			$vuse='PR';
		}
		else{
			$vuse="CM";
		}
		$year_value = dayValue($rowt['year'],$rowt['cat_value'],$vuse);

		if(trim($row['manu_date'])!=='' AND trim($row13['Date_Loss'])!==''){
			$day_value=dayValueA($row['manu_date'],substr($row13['Date_Loss'],0,10),$rowt['cat_value'],$vuse);
		}

	}

	$pdf->SetFont('helvetica', 'B', 16);
	$pdf->Cell(10,$height,'');$pdf->Cell(0,$height,'Survey Request Form '.str_pad($id,5,'0',STR_PAD_LEFT),0,1,'R');
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(165,$height,'Print Date:',0,0,'R');
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(0,$height,date('j F, Y'),'',1);
	$pdf->SetFont('helvetica', 'B', 10);
	if($row['survey_type_id']==2){
		$pdf->SetFont('helvetica', 'B', 14);
		$pdf->Cell(40,$height,'**SUBROGATION**',0,0);
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->Cell(125,$height,'Request Date:',0,0,'R');
	}
	else{
		$pdf->Cell(165,$height,'Request Date:',0,0,'R');
	}
	$pdf->SetFont('helvetica', '', 10);
	list($month,$day,$year)=explode('-',$row['open_time']);
	$req_date= new DateTime(substr($year,0,4).'-'.$month.'-'.$day);
	$req_date=date_format($req_date,'j F, Y');
	$pdf->Cell(0,$height,$req_date,0,1);
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(165,$height,'Due Date:',0,0,'R');
	$pdf->SetFont('helvetica', '', 10);
	$req_date= new DateTime(substr($year,0,4).'-'.$month.'-'.$day);
	$req_date->modify('+5 day');
	$pdf->Cell(0,$height,date_format($req_date,'j F, Y'),0,1);
	$pdf->Ln(5);

	$col1=40;
	$col2=10;
	$col3=100;
	$cola=23;
	$colb=40;
	$colc=5;

	$pdf->SetFillColor(192, 192, 192);

	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell($cola+$colb,$height,'VEHICLE INFO','B');$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola+$colb,$height,'CLAIMS DEPARTMENT','B');$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola+$colb,$height,'POLICY HOLDER','B',1);
	$pdf->SetFont('helvetica', '', 9);
	$height=4.5;
	$pdf->Cell($cola,$height,'Registration #',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$lic,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Claims #',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row2['claimNo'],0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Client',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,substr($claimant,0,20),0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Make/Model',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row2['car'],0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Date of Loss',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$date_of_incident,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Contact Person',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height, substr($row['contact_person'],0,20) ,0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Year/Color',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$yc,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Request',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$request_by_name,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Survey Address',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row['location'],0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Manufacturing',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$manu_date,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Courtesy Car',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,'',0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Policy #',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row2['pol'],0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Type/Seats',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$bs,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Body Shop',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$bodyshop,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Phone/Cell',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row['phone'],0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Transmission',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row['transmission'],0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Pictures',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$pic,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Insurance',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$insurer,0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Engine #',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row2['engine'],0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'',0);$pdf->Cell($colb,$height,'',0);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'',0);$pdf->Cell($colb,$height,'',0,1);
	$pdf->Cell($cola,$height,'Vin #',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row2['vin'],0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(2*$cola+2*$colb+$colc,$height,'CAR DAMAGE INFO & STATUS','B',1);
	$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Vehicle Use',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$vuse,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Km',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row['mileage'],0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Condition Ext',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row['cond_ext'],0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Ins/Cat Value',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$cat_value,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Car Driveable',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row3['car_driveable'],0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Condition Int',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row['cond_int'],0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Year Value',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,number_format($year_value,2),0);$pdf->SetFont('helvetica', '', 9); $pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Airbag Status',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row3['airbag_status'],0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Air-Conditioning',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,'',0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Day Value',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,number_format($day_value,2),0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Damage',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$type_dam,0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Windows',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,'',0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Repair Limit Y',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,number_format($year_value/3*2,2),0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Impact',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$type_impact,0,1);$pdf->SetFont('helvetica', '', 9);
	$pdf->Cell($cola,$height,'Repair Limit D',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,number_format($day_value/3*2,2),0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Additional Info.',0);$pdf->SetFont('helvetica', '', 9);$pdf->MultiCell(0,$height,$row['notes'],0,'L',0,1);$pdf->SetFont('helvetica', '', 9);

	$cola=5;
	$colb=100;
	$colc=20;

	$pdf->Ln($height);
	$height=6;

	$pdf->Cell($cola,$height,'',0);$pdf->Cell($colb,$height,'Parts To Be Replaced',0);$pdf->Cell($colc,$height,'R & I',0);$pdf->Cell($colc,$height,'Repair',0);$pdf->Cell($colc,$height,'Prep',0);$pdf->Cell($colc,$height,'Paint',0,1);

	for($i=1;$i<15;$i++){
		$pdf->Cell($cola,$height,$i,0);$pdf->Cell($colb,$height,'','TBLR');$pdf->Cell($colc,$height,'','TBLR');$pdf->Cell($colc,$height,'','TBLR');$pdf->Cell($colc,$height,'','TBLR');$pdf->Cell($colc,$height,'','TBLR',1);
	}

	$pdf->Cell($cola,$height,'',0);$pdf->Cell($colb,$height,'Parts To Be Repaired',0);$pdf->Cell($colc,$height,'R & I',0);$pdf->Cell($colc,$height,'Repair',0);$pdf->Cell($colc,$height,'Prep',0);$pdf->Cell($colc,$height,'Paint',0,1);

	for($i=1;$i<9;$i++){
		$pdf->Cell($cola,$height,$i,0);$pdf->Cell($colb,$height,'','TBLR');$pdf->Cell($colc,$height,'','TBLR');$pdf->Cell($colc,$height,'','TBLR');$pdf->Cell($colc,$height,'','TBLR');$pdf->Cell($colc,$height,'','TBLR',1);
	}

	$cola=23;
	$colb=40;
	$colc=5;
	$pdf->Cell($cola,$height,'Adjuster',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,$row6['name'],0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Date',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb-20,$height,'',0);$pdf->SetFont('helvetica', '', 9);$pdf->Cell($colc,$height,'',0);$pdf->Cell($cola,$height,'Signature',0);$pdf->SetFont('helvetica', 'B', 9);$pdf->Cell($colb,$height,'',0,1);$pdf->SetFont('helvetica', '', 9);

	/*
	$pdf->Cell($col1,$height,'Request Made By:');$pdf->Cell($col2,$height,'');$pdf->Cell($col3,$height,$request_by_name,0,1);
	$pdf->Cell($col1,$height,'Claim Number:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$row2['claimNo'],0,1,'L',1);
	$pdf->Cell($col1,$height,'Date of Incident:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$date_of_incident,0,1);
	$pdf->Cell($col1,$height,'Client Insurer:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$insurer,0,1,'L',1);
	$pdf->Cell($col1,$height,'Claimant:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$claimant,0,1);
	$pdf->Cell($col1,$height,'Contact Person:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$row['contact_person'],0,1,'L',1);
	$pdf->Cell($col1,$height,'Survey Address:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$row['location'],0,1);
	$pdf->Cell($col1,$height,'Tel. Number:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$row['phone'],0,1,'L',1);
	$pdf->Cell($col1,$height,'Registration Number:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$lic,0,1);
	$pdf->Cell($col1,$height,'Make, Model:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$row2['car'],0,1,'L',1);
	$pdf->Cell($col1,$height,'Year / Color:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$yc,0,1);
	$pdf->Cell($col1,$height,'Body Type / Seats:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$bs,0,1,'L',1);
	$pdf->Cell($col1,$height,'Vin Number:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$row2['vin'],0,1);
	$pdf->Cell($col1,$height,'Motor Number:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$row2['engine'],0,1,'L',1);
	$pdf->Cell($col1,$height,'Policy Number:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$row2['pol'],0,1);
	$pdf->Cell($col1,$height,'Vehicle Use:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$vuse,0,1,'L',1);
	$pdf->Cell($col1,$height,'Inception Date:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$tdate,0,1);
	$pdf->Cell($col1,$height,'Insured / Catalog Value:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$cat_value,0,1,'L',1);
	$pdf->Cell($col1,$height,'Year Value:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$year_value,0,1);
	$pdf->Cell($col1,$height,'Day Value:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$day_value,0,1,'L',1);
	$pdf->Cell($col1,$height,'Type of Impact:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$type_impact,0,1);
	$pdf->Cell($col1,$height,'Day of Damage:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$type_dam,0,1,'L',1);
	$pdf->Cell($col1,$height,'Airbag Status:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$row3['airbag_status'],0,1);
	$pdf->Cell($col1,$height,'Is the car Driveble:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$row3['car_driveable'],0,1,'L',1);
	$pdf->Cell($col1,$height,'Body Shop:');$pdf->Cell($col2,$height,'');$pdf->Cell(0,$height,$bodyshop,0,1);
	$pdf->Cell($col1,$height,'Name of Repairer:',0,0,'L',1);$pdf->Cell($col2,$height,'',0,0,'L',1);$pdf->Cell(0,$height,$row['repairer'],0,1,'L',1);
	$pdf->Cell($col1,$height,'Notes:');$pdf->Cell($col2,$height,'');$pdf->MultiCell(0,$height,$row['notes'],0,'L',0,1);*/

	$pdf->Output('survey_request'.$id.'.pdf', 'D');


?>
