<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

if($_SESSION['user_level'] < RR_LEVEL && !checkView()) {
header("Location: index.php");
exit();
}
else{
	require_once('tcpdf/tcpdf.php');
	$id = $_REQUEST['id'];
	$inv_id = $_REQUEST['id'];
	$type = $_REQUEST['type'];
	$acc=0;
	if($type==='acc'){
		$acc=1;
	}
	$adj=0;
	if($type==='adj'){
		$adj=1;
	}
	$height=6;

	$pdf = new TCPDF('P', 'mm', 'Letter', true, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nagico Road Service');
	$pdf->SetTitle('Statement');
	$pdf->SetSubject('Statement');

	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->setAutoPageBreak(true,10);

	$pdf->AddPage();
	$pdf->SetMargins(10,10,10,true);
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
	$pdf->Ln(15);
	$sql = "SELECT * FROM service_req WHERE `id`='$id'";;
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	$sqlc = "SELECT * FROM country_info";
	$rsc = mysql_query($sqlc);
	$rowc = mysql_fetch_array($rsc);

	$pdf->SetFont('helvetica', 'B', 12);
	$pdf->Cell(120,$height,'Nagico Road & Claims Services N.V.');
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(35,$height,'Invoice Date:',0,0,'R');
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(0,$height,date('j F, Y'),'',1);
	$pdf->Ln(2);
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(0,$height,'Avenida Milio Croes # 27',0,1);
	$pdf->Cell(0,$height,'Oranjestad, Aruba',0,1);
	$pdf->Cell(0,$height,'588-7000 / 191',0,1);
	$pdf->Ln(3);

	$pdf->SetLineWidth(0.5);
	$pdf->SetFillColor(0,0,0,9);
	$pdf->Cell(110,$height,'');
	$pdf->Cell(0,$height,'Bill To','LTBR',1,'',1);

	if($acc || $adj){
		$name = 'Nagico Aruba N.V.';
		$address = 'Avenida Milio Croes # 27';
		$city = 'Oranjestad';
	}
	else if($row['bill_to']!=0){
		if($row['bill_to'] > 0){
			$sql2 = "SELECT * FROM insurance_company WHERE id = '$row[bill_to]'";
			$rs2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($rs2);
		}
		else{
			$id = -1*$row['bill_to'];
			$sql2 = "SELECT * FROM clients WHERE id = '$id'";
			$rs2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($rs2);
		}
		$name = $row2['name'];
		$address = $row2['address'];
		$city = $row2['city'];
	}
	else{
		$policy=$row['pol'];
		$sql3 = "SELECT * FROM VW_VEHICLE WHERE PolicyNo LIKE '$policy'";
		//$sql3 = "SELECT * FROM vehicles_2 WHERE PolicyNo='".$row['pol']."'";
		//$rs3 = mysql_query($sql3);
		//$row3 = mysql_fetch_array($rs3);
		$params = array();
		//$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		//$rs3 = sqlsrv_query($conn,$sql3,$params,$options);
		//$row3 = sqlsrv_fetch_array($rs3);
		$rs3=mssql_query($sql3);
		$row3 = mssql_fetch_array($rs3);

		$name = $row3['Full_Name'];
		$address = $row3['Address1'];
	}
	$pdf->SetLineWidth(0.3);
	$pdf->Cell(110,$height,'');
	$pdf->Cell(0,$height,$name,'LR',1);
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(110,$height,'');
	$pdf->Cell(0,$height,$address,'LR',1);
	$pdf->Cell(110,$height,'');
	$pdf->Cell(0,$height,$city,'LRB',1);

	$pdf->Ln(12);
	$pdf->SetLineWidth(0.5);
	if($acc){ $e='-ACC';}
	else if($adj) {$e='-ADJ';}
	else {$e='';}
	$pdf->Cell(97,$height,'Invoice: '.$inv_id.$e,'LTBR',0,'',1);
	$pdf->Cell(0,$height,'Payment Terms: Net 15 Days','LTBR',1,'',1);
	if($row['job']==32){
		//tow police
		$sql4 = "SELECT * FROM towing_reason_police WHERE id=".$row['tow_reason_id'];
		$rs4 = mysql_query($sql4);
		$row4 = mysql_fetch_array($rs4);
		$pdf->Ln($height);
		$pdf->Cell(97,$height,'Reason: '.$row4['description'],'LTBR',0,'',1);
		$pdf->Cell(0,$height,'Request By: '.$row['requestedBy'],'LTBR',0,'',1);

	}


	$col1=16;
	$col2=28;


	if($acc || $adj){
		$pdf->Ln(12);
		$pdf->SetFont('helvetica', '', 9);
	$pdf->SetFillColor(20, 133, 64);
	$pdf->SetTextColor(255,255,255);
		$e='';
		$fee=0;
		if($acc){
			$e='Road Service Fee';
			if($row['accfee_amount']==0){
				$fee=795;
			}
			else{
				$fee=$row['accfee_amount'];
			}
		}
		else{
			$e='Adjusters Fee';
			if($row['adjfee_amount']==0){
				$fee=371;
			}
			else{
				$fee=$row['adjfee_amount'];
			}
		}
		$pdf->Cell($col1+10,$height,'Date','LTB',0,'C',1);
		$pdf->Cell($col1,$height,'Claims #','TB',0,'C',1);
		$pdf->Cell((3*$col2+2*$col1)-3,$height,'Description','TB',0,'C',1);
		$pdf->Cell(0,$height,'Charges','TBR',1,'R',1);
		$pdf->Cell(0,$height,'','LR',1);

		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('helvetica', '', 8);

		$pdf->Cell($col1+10,$height,$row['opendt'],'L',0,'C');
		$pdf->Cell($col1,$height,$row['claimNo'],'',0,'C');
		$pdf->Cell((3*$col2+2*$col1)-3,$height,$row['a_number'].' '.$row['car'].' '.$e,'',0,'C');
		$pdf->Cell(0,$height,number_format($fee,2),'R',1,'R');
		$pdf->Cell(0,$height,'','LR',1);


		$pdf->Cell(0,48,'','LR',1);

		//$pdf->Cell(175,$height,'Subtotal','L',0,'R');
		//$pdf->Cell(0,$height,number_format($fee,2),'R',1,'R');
		//$pdf->Cell(175,$height,'BBO 1.5%','L',0,'R');
	//	$pdf->Cell(0,$height,number_format($fee*0.015,2),'R',1,'R');
		//$pdf->Cell(175,$height,'Health Tax 3.0%','L',0,'R');
		//$pdf->Cell(0,$height,number_format($fee*0.03,2),'R',1,'R');
		//$pdf->Cell(175,$height,'BAVP 1.5%','L',0,'R');
	//	$pdf->Cell(0,$height,number_format($fee*0.015,2),'R',1,'R');
		$pdf->Cell(0,6,'','LR',1);
		$pdf->Cell(175,$height,'Total','L',0,'R');
		$pdf->Cell(0,$height,'Afl. '.number_format($fee,2),'R',1,'R');
		$pdf->Cell(0,6,'','LRB',1);
	}
	else{
		$pdf->Cell(97,$height,'PO: '.$row['po_number'],'LBR',0,'',1);
		$pdf->Cell(0,$height,'','LBR',0,'',1);
		$pdf->Ln(12);

		$pdf->SetFont('helvetica', '', 9);
		$pdf->SetFillColor(20, 133, 64);
		$pdf->SetTextColor(255,255,255);

		$pdf->Cell($col1+10,$height,'Date','LTB',0,'C',1);
		$pdf->Cell($col1,$height,'Receipt','TB',0,'C',1);
		//$pdf->Cell($col1,$height,'PO #','TB',0,'C',1);
		$pdf->Cell($col2-3,$height,'License Plate','TB',0,'C',1);
		if($row['job']==32){
			//tow police
			$pdf->Cell($col1,$height,'Color','TB',0,'C',1);
		}
		else{
			$pdf->Cell($col1,$height,'','TB',0,'C',1);
		}
		$pdf->Cell($col2,$height,'Vehicle Description','TB',0,'C',1);
		$pdf->Cell($col2,$height,'Towed From','TB',0,'C',1);
		$pdf->Cell($col2+$col1,$height,'Towed To','TB',0,'C',1);
		$pdf->Cell(0,$height,'Charges','TBR',1,'C',1);
		$pdf->Cell(0,$height,'','LR',1);

		$pdf->SetTextColor(0,0,0);
		$pdf->SetFont('helvetica', '', 8);

		$pdf->Cell($col1+10,$height,$row['opendt'],'L',0,'C');
		$pdf->Cell($col1,$height,$row['receipt'],'',0,'C');
		//$pdf->Cell($col1,$height,$row['po_number'],'',0,'C');
		$pdf->Cell($col2-3,$height,$row['a_number'],'',0,'C');

		if($row['job']==32){
			//tow police
			if($row['insured']){
				$lic = $row['a_number'];
				$pol = $row['pol'];
				$sql4 = "SELECT * FROM `vehicles_2` WHERE PolicyNo='$pol' AND LicPlateNo='$lic'";
				$rs4 = mysql_query($sql4);
				$row4 = mysql_fetch_array($rs4);
				$color = $row4['Color'];
				$yearm = $row4['YearMake'];
			}
			else{
				$lic = $row['a_number'];
				$sql4 = "SELECT * FROM `non_client_extra` WHERE id='$lic'";
				$rs4 = mysql_query($sql4);
				$row4 = mysql_fetch_array($rs4);
				$color = $row4['color'];
				$yearm = $row4['year'];
			}
			$pdf->Cell($col1,$height,$color,'',0,'C');
		}
		else{
			$pdf->Cell($col1,$height,'','',0,'C');
		}
		$pdf->Cell($col2,$height,substr($row['car'].' '.$yearm,0,18),'',0,'C');
		$pdf->Cell($col2,$height,substr($row['location'],0,30),'',0,'C');
		$pdf->Cell($col2+$col1,$height,substr($row['to_location'],0,30),'',0,'C');
		$pdf->Cell(0,$height,number_format($row['charged'],2),'R',1,'R');

		$pdf->Cell(0,48,'','LR',1);

		$pdf->Cell(175,$height,'Subtotal','L',0,'R');
		$pdf->Cell(0,$height,number_format($row['charged'],2),'R',1,'R');
		$pdf->Cell(175,$height,'BBO 1.5%','L',0,'R');
		$pdf->Cell(0,$height,number_format($row['charged']*0.015,2),'R',1,'R');
		$pdf->Cell(175,$height,'Health Tax 3.0%','L',0,'R');
		$pdf->Cell(0,$height,number_format($row['charged']*0.03,2),'R',1,'R');
		$pdf->Cell(175,$height,'BAVP 1.5%','L',0,'R');
		$pdf->Cell(0,$height,number_format($row['charged']*0.015,2),'R',1,'R');
		$pdf->Cell(0,6,'','LR',1);
		$pdf->Cell(175,$height,'Total','L',0,'R');
		$pdf->Cell(0,$height,'Afl. '.number_format(1.06*$row['charged'],2),'R',1,'R');
		$pdf->Cell(0,6,'','LRB',1);
	} //end else
	$pdf->Ln(18);
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(0,$height,'Please remit payment to Nagico Road & Claims Services N.V. Referance/ Inv #','',1,'C');
	$pdf->Ln(6);
	$pdf->Cell(0,$height,'Aruba Bank: 2614670190      KvK: 40068.0','',1);

	$pdf->Ln(12);
	$pdf->SetLeftMargin(40);
	$pdf->SetRightMargin(40);
	$pdf->Cell(0,$height,'Thank you for your business','T',1,'C');
	if($row['job']==12 || $row['job']==20 || $row['job']==90 || $row['job']==32){
		$pdf->Output('NRCS TOW INV'.$inv_id.' '.str_replace("/","",$row['po_number']).'.pdf', 'D');
	}
	else{
		$pdf->Output('Statement_'.$inv_id.'.pdf', 'D');
	}
}

?>
