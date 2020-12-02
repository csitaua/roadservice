<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
include "support/encryptionb.php";
session_start();
$country = $_REQUEST['country'];
$sKey = 'aAuqkqtslbVzi8XhR60N';

$ratecode[1] = 1;
$ratecode[2] = 0.2875;
$ratecode[3] = 0.5;
$ratecode[4] = 0.55;
$ratecode[5] = 0.5;

$noclaimdb[1] = 10;
$noclaimdb[2] = 20;
$noclaimdb[3] = 30;
$noclaimdb[4] = 40;
$noclaimdb[5] = 50;
$noclaimdb[6] = 60;
$noclaimdb[7] = 65;
$noclaimdb[8] = 70;
$noclaimdb[9] = 75;
$noclaimdb[10] = 80;

if(strcmp($country,getCCountry())!=0 && !checkAdmin() && !checkABC()){ //Wrong Country
	header( 'Location: index.php?country='.getCCountry());		
}

if(strcmp($country,'Aruba') != 0 && strcmp($country,'Bonaire') != 0 && strcmp($country,'Curacao') != 0){
	if(strcmp(getCCountry(),'Aruba')==0){
		$country='Aruba';		
	}
	else if(strcmp(getCCountry(),'Bonaire')==0){
		$country='Bonaire';		
	}
	else if(strcmp(getCCountry(),'Curacao')==0){
		$country='Curacao';		
	}
	else{
		$country='Aruba';	
	}
	header( 'Location: index.php?country='.$country);
}

if(strcmp($country,'Aruba') == 0){
	$pf=15;
	$currency = 'Afl.';	
} 
else if(strcmp($country,'Bonaire') == 0){
	if($_POST['vuse'] == 4){ //motorbike
		$pf=4.2;
	}
	else{
		$pf=15.65;
	}
	$currency = '$';	
}
else{
	if($_POST['vuse'] == 4){ //motor bike
		$pf = 15;
	}
	else{
		$pf=28;
	}
	
	$currency = 'Nafl.';
}

$sql = "SELECT * FROM vehicleuse WHERE id = '".$_POST['vuse']."'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$group = $row['group'];
							
$sql = "SELECT * FROM rategroup WHERE id = '$group'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);

if( ($_POST['coverage'] == 2 && $_POST['vuse'] != 1) || (checkAgent() && $_POST['catvalue'] > 100000 && strcmp($country,'Bonaire') != 0) ||(checkAgent() && $_POST['catvalue'] > 55870 && strcmp($country,'Bonaire') == 0)){
	$br = 0;
	$ru = 0;
	$atpl = 0;
	$fd = 0;
	$mgr = 0;
	$yp = 0;
	$gp = 0;
	$nclaimd = 0;
	$pliabr = 0;
	$netp = 0;
	$dedc = 0;	
}
else{
	if($_POST['pcoverage']==$_POST['coverage']){
		$covc = 0;
	}
	else{
		$covc = 1;
	}
	
	if($_POST['totc'] && $_POST['tot'] == 0){
		if(strcmp($country,'Bonaire')==0){
			$tot = 20;	
		}
		else{
			$tot = 30;
		}
	}
	else if(!$_POST['totc']){
		$tot = 0;	
	}
	else{
		$tot = $_POST['tot'];	
	}
	
	//Reset rateup after supercover
	if($_POST['pcoverage'] == 2 && $_POST['rateup']==5){
		$_POST['rateup'] = 0;
	}
						
	$error_prom = 'N';
	//Rate up and Promotional discount.					
	if($_POST['coverage'] == 1){ //C
		$br = $_POST['catvalue']*$row['perc_C']/100;
		/*if( ($_POST['mg'] == 0 || $covc ==1) && $_POST['vuse'] == 1 && !($_POST['mg'] == 0 && $covc ==0)){
			$_POST['mg'] = 20;	
		}*/
		if( (checkAgent() || checkNagico()) && $_POST['mg']>20){ //Max no claim for agent or regular nagico
			$_POST['mg']=20;	
			$error_prom = 'Error your current user level does not allow Promotional Discount above 20% for Comprehensive';					
		}
	}
	else if($_POST['coverage'] == 2){ //SC
		$br = ($_POST['catvalue']*$row['perc_C']/100);
		if($_POST['rateup']==0){
			if(strcmp($country,'Aruba') == 0){
				$_POST['rateup'] = 5;
			}
			else{
				$_POST['rateup'] = 15;
			}
		}
		/*if( ($_POST['mg'] == 0 || $covc ==1) && $_POST['vuse'] == 1 && !($_POST['mg'] == 0 && $covc ==0)){
			$_POST['mg'] = 30;	
		}*/
	}
	else if($_POST['coverage'] == 3){ //TP
		$cat = $_POST['catvalue'];
		$factor = $row['factor_tp'];
		$sql = "SELECT * FROM tpbp";
		$rs = mysql_query($sql);
		while($row=mysql_fetch_array($rs)){
		 	if(strcmp($country,'Bonaire') == 0){
				if($cat > convDollar($row['min']) && $cat <= convDollar($row['max'])){
					$br = $factor*$row['bp']/1.79;
					break;
				}
			}
			else{
				if($cat > $row['min'] && $cat <= $row['max']){
					$br = $factor*$row['bp'];
					break;
				}
			}
		}
		/*if( ($_POST['mg'] == 0 || $covc ==1) && $_POST['vuse'] == 1 && !($_POST['mg'] == 0 && $covc ==0)){
			$_POST['mg'] = 10;	
		}*/
		if( (checkAgent() || checkNagico()) && $_POST['mg']>10){ //Max no claim for agent or regular nagico
			$_POST['mg']=10;	
			$error_prom = 'Error your current user level does not allow Promotional Discount above 10% for Third Party';					
		}
	}
	else if($_POST['coverage'] == 4){ //TPC
		$br = ($_POST['catvalue']*($row['perc_TPC']/100));
		/*if( ($_POST['mg'] == 0 || $covc ==1) && $_POST['vuse'] == 1 && !($_POST['mg'] == 0 && $covc ==0)){
			$_POST['mg'] = 10;	
		}*/
		if( (checkAgent() || checkNagico()) && $_POST['mg']>10){ //Max no claim for agent or regular nagico
			$_POST['mg']=10;	
			$error_prom = 'Error your current user level does not allow Promotional Discount above 10% for Third Party Comprehensive';					
		}
	}
	
	if($_POST['vuse'] ==4){ //Motor Bike
		
		if(strcmp($country,'Bonaire')==0){
			$br = 268.15;
		}
		else{
			$br = 480;
		}
		if($_POST['BasicPremium'] == 305 || $_POST['BasicPremium'] == 480 || $_POST['BasicPremium'] == 3174.6 || $_POST['BasicPremium'] == 272.35){
			$br = $_POST['BasicPremium'];
		}
		$_POST['coverage'] = 3;
	}
	
	//rateup
	$ru = $br*$_POST['rateup']/100;
	
	//additional tpl
	if($_POST['liab'] == 0){
		$atpl = 0;	
	}
	else{
		$atpl = ($br+$ru)*$_POST['liab']/100;	
	}
	
	//Fleet discount
	if($_POST['fleet']){
		$fd = ($tot+$atpl+$ru+$br)*0.1;	
	}
	
	//Managerial Discount
	$mgr = ($tot+$atpl+$ru+$br-$fd)*$_POST['mg']/100;
	
	//Yearly Premium
	$yp = $tot+$atpl+$ru+$br-$fd-$mgr;
	
	//Gross Premium
	$gp = $yp*$ratecode[$_POST['ratecode']];
	
	//No Claim Discount
	$sql = "SELECT * FROM vehicleuse WHERE id='".$_POST['vuse']."'";	
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
							
	if($_POST['coverage'] == 1 or $_POST['coverage'] == 2){
		$max = $row['noclaim_max_C'];
	}
	else{
		$max = $row['noclaim_max_T'];
	}
							
	if($max < $noclaimdb[$_POST['noclaim']]){
		$nc = $max;	
	}
	else{
		$nc = $noclaimdb[$_POST['noclaim']];
	}
	$nclaimd = $gp*$nc/100;
	
	//Passenger Liability
	if($_POST['pliab']){
		if(strcmp($country,'Bonaire')==0){
			if($_POST['vuse'] == 6 || $_POST['vuse'] == 7){ //Bus or taxi
				$pliabr = 11.20*$_POST['npliab']*$ratecode[$_POST['ratecode']];
			}
			else if($_POST['vuse'] == 5){ //Heavy<br />
				$pliabr = 8.40*$_POST['npliab']*$ratecode[$_POST['ratecode']];
			}
			else if($_POST['vuse'] == 9){ //Tour Bus<br />
				$pliabr = 16.80*$_POST['npliab']*$ratecode[$_POST['ratecode']];
			}
			else{
				$pliabr = 4.90*$_POST['npliab']*$ratecode[$_POST['ratecode']];
			}
		}
		else{
			if($_POST['vuse'] == 6 || $_POST['vuse'] == 7){ //Bus or taxi
				$pliabr = 20*$_POST['npliab']*$ratecode[$_POST['ratecode']];
			}
			else if($_POST['vuse'] == 5){ //Heavy<br />
				$pliabr = 15*$_POST['npliab']*$ratecode[$_POST['ratecode']];
			}
			else if($_POST['vuse'] == 9){ //Tour Bus<br />
				$pliabr = 30*$_POST['npliab']*$ratecode[$_POST['ratecode']];
			}
			else{
				$pliabr = 8.75*$_POST['npliab']*$ratecode[$_POST['ratecode']];
			}
		}
	}
	else{
		$pliabr = 0;
	}
	
	//extra coverage
	if($_POST['ecc']){
		if(strcmp($country,'Bonaire')==0){
			if($_POST['ec'] == 1 || $_POST['ec']==2){
				$ecv = 20;
			}
			else{
				$ecv = $_POST['ecv'];	
			}
		}
		else{
			if($_POST['ec'] == 1 || $_POST['ec']==2){
				$ecv = 30;
			}
			else{
				$ecv = $_POST['ecv'];	
			}
		}
	}
	
	//net premium
	$netp = $gp+$pliabr+$ecv-$nclaimd;
	
	// tax
	$tpbeforetax = $pf+$netp;
	if(strcmp($country,'Aruba')==0){
		$tax = 0;
	}
	else if(strcmp($country,'Bonaire')==0){
		$tax = $tpbeforetax*0.09;
	}
	else if(strcmp($country,'Curacao')==0){
		$tax = $tpbeforetax*0.06;
	}
	
	
	//total premium
	$tp = $tax+$pf+$netp;
	
	//Deductible Calculated
	$dudc = 0;
	$sql = "SELECT * FROM deductible_".$country;		
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs) ){
		
		if($_POST['catvalue'] > $row['min']  && $_POST['catvalue'] <= $row['max'] ){
			
			if($_POST['coverage'] == 1 || $_POST['coverage'] == 4 ){ //C or TPC
				if($_POST['vuse'] == 1){ //private
					$dudc = $row['tpc_private'];
				}
				else{
					$dudc = $row['tpc_commercial'];	
				}
				break;
			}
			else if($_POST['coverage'] == 2){ //SC
				$dudc = $row['cs_private'];
				break;
			}
			else if($_POST['coverage'] == 3){ //TP
				if($_POST['vuse'] == 1){ //Private
					$dudc = $row['tpp'];
				}
				else if($_POST['vuse']==5 || $_POST['vuse']== 9){ //Heavy Equipment tour bus
					$dudc = $row['tp_heatb'];
				}
				else{
					$dudc = $row['tp_com'];	
				}
				break;
			}
		}
	}
	$sql = "SELECT * FROM deductible_".$country;		
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	//Third Part Calculation for other coverage
	if($_POST['vuse'] == 1){ //Private
		$dudtp = $row['tpp'];
	}
	else if($_POST['vuse']==5 || $_POST['vuse']== 9){ //Heavy Equipment tour bus
		$dudtp = $row['tp_heatb'];
	}
	else{
		$dudtp = $row['tp_com'];	
	}
	
	if($_POST['cmded']){
		$dud = $_POST['mdedc'];	
	}
	else{
		$dud = $dudc;	
	}	
	
	if(strcmp($_POST['print'],'Print')==0){
		
		require_once('tcpdf/config/lang/eng.php');
		require_once('tcpdf/tcpdf.php');
		
		$pdf = new TCPDF('P', 'mm', 'letter', true, 'UTF-8', false);
		
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nagico Insurances');
		$pdf->SetTitle('Nagico Quotation');
	
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->setAutoPageBreak(false,0);
		
		$pdf->SetFont('helvetica', 'B', 26);
	
		$pdf->AddPage();
		
		$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
		
		$pdf->Cell(80,5,'');
		$pdf->Cell(50,5,'Quotation');
		$pdf->Ln(20);
		
		$pdf->SetFont('helvetica', '', 12);
		
		$sp=40;
		
		$pdf->Cell($sp,5,'Name:',0);
		$pdf->Cell(0,5,$_POST['Name'],0,1);
		
		$pdf->Cell($sp,5,'Other Info:',0);
		$pdf->Cell(0,5,$_POST['Other'],0,1);
		
		$pdf->Cell($sp,5,'Year of Make:');
		$pdf->Cell(0,5,$_POST['yearmake'],0,1);
		
		$pdf->Cell($sp,5,'Catalog Value:');
		$pdf->Cell(0,5,$currency.' '.number_format($_POST['catvalue'],2),0,1);
		
		$pdf->Cell($sp,5,'Coverage:',0);
		$cv='';
		if($_POST['coverage']==1){
			$pdf->Cell(0,5,'Comprehensive',0,1);
			$cv='Comprehensive';
		}
		else if($_POST['coverage']==2){
			$pdf->Cell(0,5,'Comprehensive Super Cover',0,1);
			$cv='Comprehensive Super Cover';
		}
		else if($_POST['coverage']==3){
			$pdf->Cell(0,5,'Third Party',0,1);
			$cv='Third Party';
		}
		else if($_POST['coverage']==4){
			$pdf->Cell(0,5,'Third Party Limited Comprehensive',0,1);
			$cv='Third Party Limited Comprehensive';
		}
		
		$sql = "SELECT * FROM vehicleuse WHERE id=".$_POST['vuse'];
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		
		$pdf->Cell($sp,5,'Vehicle Use:',0);
		$pdf->Cell(0,5,$row['description'],0,1);
		
		$pdf->Cell($sp,5,'Rate Code:');
		
		if($_POST['ratecode']==1){
			$pdf->Cell(0,5,'1Y',0,1);	
		}
		else if($_POST['ratecode']==2){
			$pdf->Cell(0,5,'1Q',0,1);	
		}
		else if($_POST['ratecode']==3){
			$pdf->Cell(0,5,'3M50',0,1);	
		}
		else if($_POST['ratecode']==4){
			$pdf->Cell(0,5,'6M',0,1);	
		}
		else if($_POST['ratecode']==5){
			$pdf->Cell(0,5,'9M50',0,1);	
		}
		
		$pdf->Cell($sp,5,'Claim Free Years:');
		$pdf->Cell(0,5,$_POST['noclaim'],0,1);
		
		$pdf->Cell($sp,5,'Liability:');
		
		$sql = "SELECT * FROM liability WHERE extra=".$_POST['liab']." AND country='$country'";
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		
		$liab = $row['liability'];
		
		$pdf->Cell(0,5,$currency.' '.number_format($row['liability'],2),0,1);
		
		
		$pdf->Ln(10);
		
		$pdf->SetFont('helvetica', 'B', 14);
		$pdf->Cell(0,5,'Premium Calculation');
		$pdf->Ln(10);
		$pdf->SetFont('helvetica', '', 12);
		
		$sp = 50;
		$sp2 = 30;
		
		$pdf->Cell($sp,5,'Basic Premium:');
		$pdf->Cell($sp2,5,number_format($br+$ru+$atpl+$tot-$fd,2),0,1,'R');
		
		//$pdf->Cell($sp,5,'Rate Up '.$_POST['rateup'].'%:');
		//$pdf->Cell($sp2,5,number_format($ru,2),0,1,'R');
		
		//$pdf->Cell($sp,5,'Additional TPL:');
		//$pdf->Cell($sp2,5,number_format($atpl,2),0,1,'R');
		
		//$pdf->Cell($sp,5,'Tools of Trade:');
		//$pdf->Cell($sp2,5,number_format($tot,2),0,1,'R');
		
		//$pdf->Cell($sp,5,'Fleet Discount:');
		//$pdf->Cell($sp2,5,'-'.number_format($fd,2),0,1,'R');
		
		$pdf->Cell($sp,5,'Promotional Discount '.$_POST['mg'].'%:');
		$pdf->Cell($sp2,5,'-'.number_format($mgr,2),0,1,'R');
		
		$pdf->Cell($sp,5,'Gross Premium:');
		$pdf->Cell($sp2,5,number_format($yp,2),0,1,'R');
		
		$pdf->Ln(10);
		
		$pdf->Cell($sp,5,'Gross Premium:');
		$pdf->Cell($sp2,5,number_format($gp,2),0,1,'R');
		
		$pdf->Cell($sp,5,'No Claim Discount '.$nc.'%:');
		$pdf->Cell($sp2,5,'-'.number_format($nclaimd,2),0,1,'R');
		
		$pdf->Cell($sp,5,'Passenger Liability ('.$_POST['npliab'].'):');
		$pdf->Cell($sp2,5,number_format($pliabr,2),0,1,'R');
		
		if($_POST['ec']==1){
			$extrac = 'Sister Clause';	
		}
		else if($_POST['ec']==2){
			$extrac = 'Trailer Clause';	
		}
		else if($_POST['ec']==3){
			$extrac = 'Other Clause';	
		}
		
		if($ecv != 0){
			$pdf->Cell($sp,5,'Extra Coverage ('.$extrac.'):');
			$pdf->Cell($sp2,5,number_format($ecv,2),0,1,'R');
		}
			
		$pdf->Cell($sp,5,'Net Premium:');
		$pdf->Cell($sp2,5,number_format($netp,2),0,1,'R');
	
		$pdf->Cell($sp,5,'Policy Fee:');
		$pdf->Cell($sp2,5,number_format($pf,2),0,1,'R');
		
		if(strcmp($country,'Aruba')==0){
			$pdf->Cell($sp,5,'Tax:');
		}
		else if(strcmp($country,'Bonaire')==0){
			$pdf->Cell($sp,5,'ABB 9%:');
		}
		else if(strcmp($country,'Curacao')==0){
			$pdf->Cell($sp,5,'OB 6%:');
		}
		$pdf->Cell($sp2,5,number_format($tax,2),0,1,'R');
		
		$pdf->SetFont('helvetica', 'B', 12);
		
		$pdf->Cell($sp,5,'Total Premium:');
		$pdf->Cell($sp2,5,$currency.' '.number_format($tp,2),0,1,'R');
		
		$pdf->Cell($sp,5,'Deductible:');
		$pdf->Cell($sp2,5,$currency.' '.number_format($dud,2),0,0,'R');
		
		$pdf->Cell(0,5,'('.$cv.')',0,1);
		if($_POST['coverage']==1 || $_POST['coverage']==2 || $_POST['coverage']==4){
			$pdf->Cell($sp,5,'Deductible:');
			if(strcmp($country,'Bonaire')==0){
				$pdf->Cell($sp2,5,$currency.' '.number_format(84,2),0,0,'R');
			}
			else{
				$pdf->Cell($sp2,5,$currency.' '.number_format(150,2),0,0,'R');
			}
			$pdf->Cell(0,5,'(Third Party)',0,1);	
		}
		
		$pdf->Ln(20);
		
		$pdf->SetFont('helvetica', '', 10);
		$pdf->Cell(68,5,'Quotation date: '.date("F j, Y"),0,1);
		$pdf->Cell(0,5,'*Quotation is valid for 15 days',0,1);
		$pdf->Cell(0,5,'*Quotation is subject to Nagico receiving the following document/information:',0,1);
		$pdf->Cell(15,5,'');
		$pdf->Cell(0,5,'-Completed and signed application form',0,1);
		$pdf->Cell(15,5,'');
		$pdf->Cell(0,5,'-No claim discount letter from previous insurance company',0,1);
		$pdf->Cell(15,5,'');
		$pdf->Cell(0,5,"-Valid driver's license",0,1);
		$pdf->Cell(15,5,'');
		$pdf->Cell(0,5,"-Valid vehicle registration and inspection card",0,1);
		
		
		$pdf->SetXY(10,-25);
		
		$pdf->Cell(125,5,'__________________________________');
		$pdf->Cell(0,5,'__________________________________');
		
		$pdf->SetXY(10,-20);
		$pdf->SetFont('helvetica', 'I', 9);
		
		$pdf->Cell(125,5,'Prepared By:');
		$pdf->Cell(0,5,'Client Approval:',0,1);
		
		$sql = "SELECT * FROM users WHERE id=".$_SESSION['user_id'];
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		$pdf->Cell(125,5,$row['user_name'].'\Nagico '.$country);
		$pdf->Cell(0,5,$_POST['Name']);
		$pdf->SetXY(10,-20);
		//$pdf->Cell(68,5,date("F j, Y"),0,0,'R');
		
		$pdf->SetFont('helvetica', '', 6);
		$pdf->SetXY(-10,-10);
		$pdf->Cell(0,5,'Control: '.encrypt($_POST['Name'].'~'.$_POST['catvalue'].'~'.$_POST['coverage'].'~'.$_POST['vuse'].'~'.$liab.'~'.$tp.'~'.$dud.'~'.$row['user_name'].'~'.$country, $sKey),0,0,'R');
		
		$pdf->Output($_POST['Name'].'_Qoute.pdf','D');
	}
	
	if(strcmp($_POST['print_covernote'],'Print Covernote')==0){
		require_once('tcpdf/config/lang/eng.php');
		require_once('tcpdf/tcpdf.php');
		
		$pdf = new TCPDF('P', 'mm', 'letter', true, 'UTF-8', false);
		
		$t_id = str_pad($_SESSION['user_id'], 3 , '0', STR_PAD_LEFT);
		$sql4 = "SELECT * FROM covernote WHERE covernoteno LIKE '$t_id%' ORDER BY covernoteno DESC";
		$rs4 = mysql_query($sql4);
		if(mysql_num_rows($rs4) != 0){
			$row4 = mysql_fetch_array($rs4);
			$last_id = $row4['covernoteno'];
			$new_id = substr($last_id,3);
			$new_id++;
		}
		else{
			$new_id = 1;	
		}
		$policy_no =str_pad($_SESSION['user_id'], 3 , '0', STR_PAD_LEFT).str_pad($new_id, 5 , '0', STR_PAD_LEFT);
		
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nagico Insurances');
		$pdf->SetTitle('Nagico Covernote');
	
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->setAutoPageBreak(false,0);
	
		$pdf->AddPage();
		
		$pdf->SetMargins(20,10);
		$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
		$pdf->Cell(0,5,$policy_no,'',2,'R');
		
		$pdf->Ln(10);
		
		$pdf->SetFont('helvetica', 'B', 18);
		$pdf->Cell(0,5,'RECEIPT AND COVERNOTE','',2,'C');
		
		$pdf->SetFont('helvetica', '', 11);
		
		//$pdf->Ln(37);
		
		$pdf->Ln(15);
		$pdf->SetFont('helvetica', '', 11);
		$pdf->Cell(40,6,'POLICY NO.');
		$pdf->Cell(70,6,$_POST['pon']);
		$pdf->Cell(30,6,'AGENT:');
		$pdf->Cell(0,6,getAgent(),0,1);
		$pdf->Cell(110,6,'');
		$pdf->Cell(30,6,'DATE:');
		$now = date('Y').'-'.date('m').'-'.date('d').' '.date('H').':'.date('i').':00';
		$pdf->Cell(0,6,date('d').' '.date('F').' '.date('Y'),0,1);
		$pdf->Ln(5);
		$pdf->Cell(50,6,'NAME OF THE INSURED:');
		$pdf->Cell(0,6,$_POST['Name'],0,1);
		$pdf->Cell(50,6,'ADDRESS:');
		$pdf->Cell(0,6,$_POST['address'],0,1);
		$pdf->Ln(3);
		
		if($_POST['coverage']==1){
			$cv='Comprehensive';
		}
		else if($_POST['coverage']==2){
			$cv='Comprehensive Super Cover';
		}
		else if($_POST['coverage']==3){
			$cv='Third Party';
		}
		else if($_POST['coverage']==4){
			$cv='Third Party Limited Comprehensive';
		}
		
		//$pdf->Cell(0,6,'Having applied for the insurance on the motor vehicle described below and having paid the sum of',0,1);
		//$pdf->Cell(0,6,'AWG '.number_format($_POST['payment'],2).' the Insurance is hereby provisionally held in force from '.date('g').':'.date('i').' '.date('a'),0,1);
		$pdf->Write(5,'Having applied for the insurance on the motor vehicle described below and having paid the sum of ');
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->Write(5,$currency.' '.number_format($_POST['payment'],2));
		$pdf->SetFont('helvetica', '', 11);
		$pdf->Write(5,' the Insurance is hereby provisionally held in force from ');
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->Write(5,date('g').':'.date('i').' '.date('a'));
		$pdf->SetFont('helvetica', '', 11);
		$pdf->Write(5," on the date mentioned below for thirthy (30)* days in terms of the Company's usual form of ");
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->Write(5,$cv." policy ");
		$pdf->SetFont('helvetica', '', 11);
		$pdf->Write(5,"applicable thereto, unless this insurance be terminated by written notice to the proposer at the above address in which case the insurance shall tereupon cease and a proportion of the annual premium payable will be charged for the time this insurance has been in force.");
		$pdf->SetLeftMargin(15);
		$pdf->SetRightMargin(15);
		$pdf->Ln(9);
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->Cell(38,6,'MAKE OF VEHICLE','LTRB',0,'C');
		$pdf->Cell(23,6,'TYPE','LTRB',0,'C');
		$pdf->Cell(13,6,'YEAR','LTRB',0,'C');
		$pdf->Cell(24,6,'REG. NO.','LTRB',0,'C');
		$pdf->Cell(42,6,'CHASSIS NO.','LTRB',0,'C');
		$pdf->Cell(23,6,'VALUE','LTRB',0,'C');
		$pdf->Cell(23,6,'COLOR','LTRB',1,'C');
		$pdf->SetFont('helvetica', '', 11);
		$pdf->MultiCell(38,12,$_POST['make_vehicle'],'LTRB','C',false,0);
		$pdf->MultiCell(23,12,$_POST['type'],'LTRB','C',false,0);
		$pdf->MultiCell(13,12,$_POST['year_vehicle'],'LTRB','C',false,0);
		$pdf->MultiCell(24,12,$_POST['reg_mark'],'LTRB','C',false,0);
		$pdf->MultiCell(42,12,$_POST['chassis_no'],'LTRB','C',false,0);
		$pdf->MultiCell(23,12,number_format($_POST['catvalue'],2),'LTRB','C',false,0);
		$pdf->MultiCell(23,12,$_POST['color'],'LTRB','C',false);
		
		$pdf->Ln(5);
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->Cell(0,5,'Authorized Drivers','LRTB',1);
		$pdf->SetFont('helvetica', '', 11);
		$pdf->Cell(0,5,'1. '.$_POST['auth1'],'LR',1);
		$pdf->Cell(0,5,'2. '.$_POST['auth2'],'LR',1);
		
		if($_POST['authany']){
			$pdf->Cell(0,6,'3. Or any authorized driver','LRB',1);
		}
		else{		
			$pdf->Cell(0,6,'3. '.$_POST['auth3'],'LRB',1);
		}
		
		$pdf->Cell(40,6,'Period of Insurance');
		if($_POST['ratecode']==1){
			$pdf->Cell(50,6,'1Y');	
			$rq = '1Y';
		}
		else if($_POST['ratecode']==2){
			$pdf->Cell(50,6,'1Q');	
			$rq = '1Q';
		}
		else if($_POST['ratecode']==3){
			$pdf->Cell(50,6,'3M50');
			$rq = '3M50';	
		}
		else if($_POST['ratecode']==4){
			$pdf->Cell(50,6,'6M');	
			$rq = '6M';
		}
		else if($_POST['ratecode']==5){
			$pdf->Cell(50,6,'9M50');	
			$rq = '9M50';
		}
		list($year,$month,$day) = explode("-",$_POST['date_from']);
		list($yeart,$montht,$dayt) = explode("-",$_POST['date_to']);
		$pdf->Cell(0,6,'From   '.date('d',mktime(0,0,0,$month,$day,$year)).' '.date('F',mktime(0,0,0,$month,$day,$year)).' '.date('Y',mktime(0,0,0,$month,$day,$year)).'    to    '.date('d',mktime(0,0,0,$montht,$dayt,$yeart)).' '.date('F',mktime(0,0,0,$montht,$dayt,$yeart)).' '.date('Y',mktime(0,0,0,$montht,$dayt,$yeart)));	
		$pdf->Ln(6);
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->Cell(55,6,'YEARLY PREMIUM:','LT');
		$pdf->Cell(55,6,$currency.' '.number_format($yp,2),'T');
		$pdf->SetFont('helvetica', '', 11);
		$pdf->Cell(35,6,'Deductible:','T');
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->Cell(0,6,$currency.' '.number_format($dud,2),'TR',1);
		
		$pdf->SetFont('helvetica', '', 11);	
		$pdf->Cell(55,6,$rq.' Premium:','L');
		$pdf->Cell(55,6,$currency.' '.number_format($gp,2));
		$pdf->Cell(35,6,'Paid:');
		$pdf->Cell(0,6,$currency.' '.number_format($_POST['payment'],2),'R',1);
		
		$pdf->Cell(55,6,number_format($nc,2).'% NCD','L');	
		$pdf->Cell(55,6,$currency.' '.number_format($nclaimd,2));
		$pdf->Cell(35,6,'Balance:');
		$pdf->Cell(0,6,$currency.' '.number_format($tp-$_POST['payment'],2),'R',1);
		
		$pdf->Cell(55,6,'Passengers Liability ('.$_POST['npliab'].'):','L');	
		$pdf->Cell(0,6,$currency.' '.number_format($pliabr,2),'R',1);
		
		$pdf->Cell(55,6,'Policy Cost:','L');	
		$pdf->Cell(55,6,$currency.' '.number_format($pf,2));
		$pdf->Cell(35,6,'Assignee:');
		$pdf->Cell(0,6,$_POST['assignee'],'R',1);
		
		$pdf->Cell(55,6,'Net Premium','L');	
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->Cell(0,6,$currency.' '.number_format($tp,2),'R',1);
		$pdf->SetFont('helvetica', '', 11);
		list($year,$month,$day) = explode("-",$_POST['prem_date']);
		$pdf->Cell(0,6,'* ANY PREMIUM MUST BE PAID ON OR BEFORE '.date('d',mktime(0,0,0,$month,$day,$year)).' '.date('F',mktime(0,0,0,$month,$day,$year)).' '.date('Y',mktime(0,0,0,$month,$day,$year)),'LRB',1);	
		$pdf->Ln(3);		
		$pdf->Cell(0,6,'I hereby certify that this covernote satisfies the requirements of the relevant law applicable in '.$country,0,1,'C');			
		
		$pdf->SetFont('helvetica', 'B', 11);
		$pdf->SetLeftMargin(20);
		$pdf->SetRightMargin(20);
		$pdf->Write(6,'* PLEASE NOTE: YOUR POLICY IS ONLY FOR THE PERIOD STATED ABOVE. THE FULL BALANCE OF THE YEARLY PREMIUM MUST BE PAID ON OR BEFORE THE DATE ENDING AS ABOVE. IF NOT, NO COVERAGE SHALL EXIST THEREAFTER.','',false,'C');
		
		$pdf->SetFont('helvetica', '', 11);
		$pdf->SetLeftMargin(15);
		$pdf->SetRightMargin(15);
		$pdf->Ln(12);
		
		$pdf->Cell(120,6,'Signed for and on behalf of the Company');
		$pdf->Cell(0,6,'This '.date('d').' '.date('F').' '.date('Y'),0,1);
		$pdf->Ln(12);
		$pdf->Cell(120,6,'');
		$pdf->Cell(0,6,getUserFName(),'T',1);
		
		$pdf->SetY(-12);
		$pdf->SetFont('helvetica', '', 9);
		$pdf->Cell(0,6,'*If you have not received your policy after this date please notify this office at once*',0,1,'C');
		$agent = getAgent();
		$balance = $tp-$_POST['payment'];
		
		
		
		
		$sql4 = "INSERT INTO `covernote`(`covernoteno`,`policyno`, `agent`, `date_printed`, `name`, `address`, `make`, `year`, `registration_mark`, `chassis_no`, `type`, `value`, `color`, `paid`, `period`, `from`, `to`, `yearly_premium`, `premium`, `ncd_perc`, `ncd`, `passenger_liab_num`, `passenger_liab`, `policy_cost`, `net_premium`, `deductible`, `Balance`, `Assignee`, `auth1`, `auth2`, `auth3`, `authany`, `coverage`, `vuse`) VALUES ('$policy_no', '$_POST[pon]', '$agent','$now', '$_POST[Name]', '$_POST[address]', '$_POST[make_vehicle]', '$_POST[year_vehicle]', '$_POST[reg_mark]', '$_POST[chassis_no]', '$_POST[type]', '$_POST[catvalue]', '$_POST[color]', '$_POST[payment]', '$rq', '$_POST[date_from]', '$_POST[date_to]', '$yp', '$gp', '$nc', '$nclaimd', '$_POST[npliab]', '$pliabr', '$pf', '$tp', '$dud', '$balance',  '$_POST[assignee]', '$_POST[auth1]', '$_POST[auth2]', '$_POST[auth3]', '$_POST[authany]', '$_POST[coverage]', '$_POST[vuse]')";
		mysql_query($sql4);
		$pdf->Output($_POST['Name'].'_Covernote.pdf','D');		
		
	}
} //end check errors 



echo menu();

?>

<form name="agentcalc" action="index.php?country=<?php echo $country;?>" method="post">
  <table>
  	<?php
		if($_POST['coverage'] == 2 && $_POST['vuse'] != 1){
			echo '
				<tr>
					<td><span style="color:red">Error Super Cover only valid for Private Use<span></td>
					<td>&nbsp;</td>
				</tr>
			';	
		}
		if (checkAgent() && $_POST['catvalue'] > 100000 && strcmp($country,'Bonaire') != 0){
			echo '
				<tr>
					<td><span style="color:red">Error catalog value above '.$currency.' 100,000.00 please contact Nagico Office<span></td>
					<td>&nbsp;</td>
				</tr>
			';	
		}
		if (checkAgent() && $_POST['catvalue'] > 55870 && strcmp($country,'Bonaire') == 0){
			echo '
				<tr>
					<td><span style="color:red">Error catalog value above '.$currency.' 55,870.00 please contact Nagico Office<span></td>
					<td>&nbsp;</td>
				</tr>
			';	
		}
		if(strcmp($error_prom,'N') != 0){
			echo '
				<tr>
					<td colspan="2"><span style="color:red">'.$error_prom.'</td>
				</tr>
			';	
		}
	?>
  	<tr>
    	<td>Development Country: <?php echo $country.' ('.getLevel().')'?></td>
        <td>User: <?php echo getUserFName()?></td>
    </tr>
	<tr>
    	<td width="500" valign="top">
        	<table width="100%" border="1">
            	<tr style="border:0">
                	<td colspan="3" align="center" style="border:0;color:#148540"><h3>Applicant's Info</h3></td>
                </tr>
                <tr><td colspan="3" style="border:0">&nbsp;</td></tr>
            	<tr style="border:0">
                    <td align="right" width="75" style="border:0">Name:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td width="415" style="border:0"><input type="text" style="background-color:#FAD090" name="Name" size="50" value="<?php echo $_POST['Name']?>"/></td>
              	</tr>
                <tr style="border:0">
                    <td align="right" width="75" style="border:0">Other Info:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td width="415" style="border:0"><input type="text" style="background-color:#FAD090" name="Other" size="50" value="<?php echo $_POST['Other']?>"/>
                    </td>
              	</tr>
           	</table>
   	  </td>
        <td width="375" rowspan="2" valign="top">
        	<table width="100%" border="1">
            	<tr style="border:0">
                	<td colspan="3" align="center" style="border:0"><h3 style="color:#148540">Premium Breakdown</h3></td>
                </tr>
                <input type="hidden" name="pcoverage" value="<?php echo $_POST['coverage']?>" />
                <tr style="border:0"><td style="border:0">&nbsp;</td></tr>
                <tr style="border:0">
                	<td style="border:0">Basic Premium:</td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <?php if($_POST['vuse'] != 4){ ?>
                  	<td style="border:0"><input type="text" align="right" name="BasicPremium" size="15" style="text-align:right" disabled="disabled" value="<?php echo number_format($br,2);?>"/></td>
                    <?php }
                    else { ?>
                    <td style="border:0">
                    	<select  name="BasicPremium" style="background-color:#FAD090; width:7em">
                        	<option value="<?php if(strcmp($country,'Bonaire')==0) {echo 174.60;} else {echo 305;}?>" <?php if ($br==305 || $br == 174.6){echo 'selected="selected"';}?>><?php if(strcmp($country,'Bonaire')==0) {echo number_format(174.6,2);} else {echo number_format(305,2);}?></option>
                            <option value="<?php if(strcmp($country,'Bonaire')==0) {echo 272.35;} else {echo 480;}?>" <?php if ($br==480 || $br == 272.35){echo 'selected="selected"';}?>><?php if(strcmp($country,'Bonaire')==0) {echo number_format(272.35,2);} else {echo number_format(480,2);}?></option>
                        </select>
                    </td>
                    <?php } ?>
                </tr>
                <tr style="border:0">
                	<td style="border:0">Rate Up%: &nbsp;
                    <select name="rateup" style="background-color:#FAD090">
                    <option value="0" <?php if ($_POST['rateup'] == 0) {echo 'selected="selected"';}?>>00%</option>
                    <option value="5" <?php if ($_POST['rateup'] == 5) {echo 'selected="selected"';}?>>05%</option>
                    <option value="10" <?php if ($_POST['rateup'] == 10) {echo 'selected="selected"';}?>>10%</option>
                    <option value="15" <?php if ($_POST['rateup'] == 15) {echo 'selected="selected"';}?>>15%</option>
                    <option value="20" <?php if ($_POST['rateup'] == 20) {echo 'selected="selected"';}?>>20%</option>
                    <option value="25" <?php if ($_POST['rateup'] == 25) {echo 'selected="selected"';}?>>25%</option>
                    <option value="30" <?php if ($_POST['rateup'] == 30) {echo 'selected="selected"';}?>>30%</option>
                    <option value="35" <?php if ($_POST['rateup'] == 35) {echo 'selected="selected"';}?>>35%</option>
                    <option value="40" <?php if ($_POST['rateup'] == 40) {echo 'selected="selected"';}?>>40%</option>
                    <option value="45" <?php if ($_POST['rateup'] == 45) {echo 'selected="selected"';}?>>45%</option>
                    <option value="50" <?php if ($_POST['rateup'] == 50) {echo 'selected="selected"';}?>>50%</option>
                    <option value="55" <?php if ($_POST['rateup'] == 55) {echo 'selected="selected"';}?>>55%</option>
                    <option value="60" <?php if ($_POST['rateup'] == 60) {echo 'selected="selected"';}?>>60%</option>
                    <option value="65" <?php if ($_POST['rateup'] == 65) {echo 'selected="selected"';}?>>65%</option>
                    <option value="70" <?php if ($_POST['rateup'] == 70) {echo 'selected="selected"';}?>>70%</option>
                    <option value="75" <?php if ($_POST['rateup'] == 75) {echo 'selected="selected"';}?>>75%</option>
                    <option value="80" <?php if ($_POST['rateup'] == 80) {echo 'selected="selected"';}?>>80%</option>
                    <option value="85" <?php if ($_POST['rateup'] == 85) {echo 'selected="selected"';}?>>85%</option>
                    <option value="90" <?php if ($_POST['rateup'] == 90) {echo 'selected="selected"';}?>>90%</option>
                    <option value="95" <?php if ($_POST['rateup'] == 95) {echo 'selected="selected"';}?>>95%</option>
                    <option value="100" <?php if ($_POST['rateup'] == 100) {echo 'selected="selected"';}?>>100%</option>
                    </select>
                    </td>
                    <td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right" name="rateupr" size="15" disabled="disabled" value="<?php echo number_format($ru,2);?>"/></td>
                </tr>
                <tr>
                	<td style="border:0">Additional TPL.:</td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right" name="atpl" disabled="disabled" size="15"value="<?php echo number_format($atpl,2);?>"/></td>
                </tr>
                 <tr>
                	<td style="border:0">Tools of Trade:&nbsp;<input type="checkbox" name="totc" <?php if($_POST['totc']) echo 'checked="checked"'?> /></td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right;background-color:#FAD090" name="tot" size="15"value="<?php 
					if($_POST['totc'] && $_POST['tot'] == 0 ){
						if(strcmp($country,'Bonaire')==0){
							echo number_format(20,2);
						}
						else{
							echo number_format(30,2);
						}
					}
					else if(!$_POST['totc']){
						echo number_format(0,2);
					}
					else {
						echo number_format($_POST['tot'],2);
					}
					?>"/>
                    </td>
                </tr>
                 <tr>
                	<td style="border:0">Fleet Discount:&nbsp;<input type="checkbox" name="fleet" <?php if($_POST['fleet']) echo 'checked="checked"'?> /></td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right; color:red" name="fleetdisc" disabled="disabled" size="15"value="<?php echo number_format($fd,2);?>"/></td>
                </tr>
                 <tr style="border:0">
                	<td style="border:0">Promotional Discount %: &nbsp;
                    <select name="mg" style="background-color:#FAD090">
                    <option value="0" <?php if ($_POST['mg'] == 0) {echo 'selected="selected"';}?>>00%</option>
                    <option value="5" <?php if ($_POST['mg'] == 5) {echo 'selected="selected"';}?>>05%</option>
                    <option value="10" <?php if ($_POST['mg'] == 10) {echo 'selected="selected"';}?>>10%</option>
                    <option value="15" <?php if ($_POST['mg'] == 15) {echo 'selected="selected"';}?>>15%</option>
                    <option value="20" <?php if ($_POST['mg'] == 20) {echo 'selected="selected"';}?>>20%</option>
                    <option value="25" <?php if ($_POST['mg'] == 25) {echo 'selected="selected"';}?>>25%</option>
                    <option value="30" <?php if ($_POST['mg'] == 30) {echo 'selected="selected"';}?>>30%</option>
                     <option value="35" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 35) {echo 'selected="selected"';}?>>35%</option>
                    <option value="40" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 40) {echo 'selected="selected"';}?>>40%</option>
                    <option value="45" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 45) {echo 'selected="selected"';}?>>45%</option>
                    <option value="50" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 50) {echo 'selected="selected"';}?>>50%</option>
                    <option value="55" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 55) {echo 'selected="selected"';}?>>55%</option>
                    <option value="60" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 60) {echo 'selected="selected"';}?>>60%</option>
                    <option value="65" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 65) {echo 'selected="selected"';}?>>65%</option>
                    <option value="70" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 70) {echo 'selected="selected"';}?>>70%</option>
                    <option value="75" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 75) {echo 'selected="selected"';}?>>75%</option>
                    <option value="80" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 80) {echo 'selected="selected"';}?>>80%</option>
                    <option value="85" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 85) {echo 'selected="selected"';}?>>85%</option>
                    <option value="90" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 90) {echo 'selected="selected"';}?>>90%</option>
                    <option value="95" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 95) {echo 'selected="selected"';}?>>95%</option>
                    <option value="100" <?php if(checkAgent() || checkNagico()) echo'disabled="disabled"' ?> <?php if ($_POST['mg'] == 100) {echo 'selected="selected"';}?>>100%</option>
                    </select>
                    </td>
                    <td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right; color:red" name="mgr" size="15" disabled="disabled" value="<?php echo number_format($mgr,2);?>"/></td>
                </tr>
               	 <tr>
                	<td style="border:0">Yearly Premium:</td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right" disabled="disabled" name="yp" size="15"value="<?php echo number_format($yp,2);?>"/></td>
                 </tr>
                  <tr>
                	<td style="border:0">Gross Premium:</td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" disabled="disabled" style="text-align:right" name="gp" size="15"value="<?php echo number_format($gp,2);?>"/></td>
                 </tr>  
                  <tr>				
                	<td style="border:0">No Claim Discount <?php echo $nc?>%:</td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right; color:red" disabled="disabled" name="nclaimd" size="15"value="<?php echo number_format($nclaimd,2);?>"/></td>
                 </tr>  
                  <tr>
                	<td style="border:0">Pass. Liability:&nbsp; <input type="checkbox" name="pliab" <?php if($_POST['pliab']) echo 'checked="checked"';?> /> &nbsp; 
                    <input type="text" size="2" style="background-color:#FAD090" name="npliab" value="<?php echo $_POST['npliab'];?>"/>
                    </td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right" disabled="disabled"  name="pliabr" size="15"value="<?php echo number_format($pliabr,2);?>"/></td>
                 </tr>
                 <tr>
                	<td style="border:0">Extra Coverage:&nbsp; <input type="checkbox" name="ecc" <?php if($_POST['ecc']) echo 'checked="checked"';?> /> &nbsp; 
                    <select style="background-color:#FAD090" name="ec"/>
                    	<option value="1" <?php if ($_POST['ec']==1) echo 'selected="selected"'?>>Sister Clause</option>
                        <option value="2" <?php if ($_POST['ec']==2) echo 'selected="selected"'?>>Trailer Clause</option>
                        <option value="3" <?php if ($_POST['ec']==3) echo 'selected="selected"'?>>Other</option>
                    </select>
                    </td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right" name="ecv" size="15"value="<?php echo number_format($ecv,2);?>"/></td>
                 </tr>
                  <tr>
                	<td style="border:0">Net Premium:</td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right" disabled="disabled" name="netp" size="15"value="<?php echo number_format($netp,2);?>"/></td>
                 </tr>   
                 <tr>
                	<td style="border:0">Policy Fee:</td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right" disabled="disabled" name="pf" size="15"value="<?php echo number_format($pf,2);?>"/></td>
                 </tr> 
                 <tr>
                	<td style="border:0"><?php 
						if(strcmp($country,'Aruba')==0){
							echo 'Tax 0';	
						}
						else if(strcmp($country,'Bonaire')==0){
							echo 'ABB 9';	
						}
						if(strcmp($country,'Curacao')==0){
							echo 'OB 6';	
						}
					?>%:</td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right" disabled="disabled" name="tax" size="15"value="<?php echo number_format($tax,2);?>"/></td>
                 </tr> 
                   <tr>
                	<td style="border:0">Total Premium:</td>
                   	<td width="5" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" style="text-align:right" disabled="disabled" name="totalprem" size="15"value="<?php echo number_format($tp,2);?>"/></td>
                 </tr>   
            </table>
        </td>
    </tr>
    <tr>
    	<td>
    		<table width="100%" border="1">
            	<tr style="border:0">
                	<td colspan="3" align="center" style="border:0"><h3 style="color:#148540">Coverage Info</h3></td>
                </tr>
                <tr><td colspan="3" style="border:0">&nbsp;</td></tr>
                <tr style="border:0">
                    <td align="right" width="150" style="border:0">Coverage:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td style="border:0"p><select name="coverage" style="background-color:#FAD090">
                    <option value="1" <?php if ($_POST['coverage'] == 1) {echo 'selected="selected"';}?>>C (Comprehensive)</option>
                    <option value="2" <?php if ($_POST['coverage'] == 2) {echo 'selected="selected"';}?>>CS (Comprehensive Super Cover)</option>
                    <option value="3" <?php if ($_POST['coverage'] == 3) {echo 'selected="selected"';}?>>TP (Third Party)</option>
                    <option value="4" <?php if ($_POST['coverage'] == 4) {echo 'selected="selected"';}?>>TPC (Third Party Limited Comprehensive) </option>
                    
                    </select></td>
              	</tr>
            	<tr style="border:0">
                    <td align="right" width="150" style="border:0">Vehicle Use:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td style="border:0"p><select name="vuse" style="background-color:#FAD090">
                    <?php
						$sql = "SELECT * FROM vehicleuse";
						$rs = mysql_query($sql);
						while($row=mysql_fetch_array($rs)){
							if($_POST['vuse'] == $row['id']){
								echo '<option value="'.$row['id'].'" selected="selected">'.$row['code'].' ('.$row['description'].')</option>';	
							}
							else{
								if(checkAgent() && $row['id'] != 1 && $row['id'] !=2){
									echo '<option disabled="disabled" value="'.$row['id'].'">'.$row['code'].' ('.$row['description'].')</option>';	
								}
								else{
									echo '<option value="'.$row['id'].'">'.$row['code'].' ('.$row['description'].')</option>';					}
							}
						}
					?>
                    </select></td>
              	</tr>
                <tr style="border:0">
                    <td align="right" width="150" style="border:0">RateCode:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td style="border:0"><select name="ratecode" style="background-color:#FAD090">
                    <option value="1" <?php 
						if (checkAgent()){
							echo 'selected="selected"';
						}	
						else if ($_POST['ratecode'] == 1) {echo 'selected="selected"';}
						
					?>>1Y</option>
                    <option value="3" <?php 
					if (checkAgent()){
							 echo 'disabled="disabled"';
					}	
					else if ($_POST['ratecode'] == 3) {echo 'selected="selected"';}
					?>>3M50</option>
                    <option value="5" <?php 
					if (checkAgent()){
							 echo 'disabled="disabled"';
					}	
					else if ($_POST['ratecode'] == 5) {echo 'selected="selected"';}
					?>>9M50</option>
                    </select>
                    </td>
              	</tr>
                 <tr style="border:0">
                    <td align="right" width="150" style="border:0">Claim Free Years:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td style="border:0"><select name="noclaim" style="background-color:#FAD090">
                    <option value="0"<?php  if (checkAgent()){ echo 'disabled="disabled"';}?> <?php if ($_POST['noclaim'] == 0) {echo 'selected="selected"';}?>>0</option>
                    <option value="1" <?php if ($_POST['noclaim'] == 1 || (!$_POST['noclaim'] && checkAgent())) {echo 'selected="selected"';}?>>1</option>
                    <option value="2" <?php if ($_POST['noclaim'] == 2) {echo 'selected="selected"';}?>>2</option>
                    <option value="3" <?php if ($_POST['noclaim'] == 3) {echo 'selected="selected"';}?>>3</option>
                    <option value="4" <?php if ($_POST['noclaim'] == 4) {echo 'selected="selected"';}?>>4</option>
                    <option value="5" <?php if ($_POST['noclaim'] == 5) {echo 'selected="selected"';}?>>5</option>
                    <option value="6" <?php if ($_POST['noclaim'] == 6) {echo 'selected="selected"';}?>>6</option>
                    <option value="7" <?php if ($_POST['noclaim'] == 7) {echo 'selected="selected"';}?>>7</option>
                    <option value="8" <?php if ($_POST['noclaim'] == 8) {echo 'selected="selected"';}?>>8</option>
                    <option value="9" <?php if ($_POST['noclaim'] == 9) {echo 'selected="selected"';}?>>9</option>
                    <option value="10" <?php if ($_POST['noclaim'] == 10) {echo 'selected="selected"';}?>>10 or More</option>
                    </select>
                    </td>
              	</tr>
                <tr style="border:0">
                    <td align="right" width="150" style="border:0">Liability:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td style="border:0"><select name="liab" style="background-color:#FAD090">
                    <?php
						$sql = "SELECT * FROM liability WHERE country='$country'";
						$rs = mysql_query($sql);
						while($row=mysql_fetch_array($rs)){
							if($row['extra']==$_POST['liab']){
								echo '<option value="'.$row['extra'].'" selected="selected">'.$currency.' '.number_format($row['liability'],2).'</option>';	
							}
							else{
								echo '<option value="'.$row['extra'].'">'.$currency.' '.number_format($row['liability'],2).'</option>';
							}
						}
					?>
                    </select>
                    </td>
              	</tr>
                <tr style="border:0">
                    <td align="right" width="150" style="border:0">Year of Make:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td style="border:0"><select name="yearmake" style="background-color:#FAD090">
                   		<?php
							for($i = (date('Y')+2); $i >= 1960 ; $i--){
								if($_POST['yearmake'] == $i){
									echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
								}
								else{
									echo '<option value="'.$i.'">'.$i.'</option>';
								}
							}
						?>
                    </select>
                    </td>
              	</tr>
                <tr style="border:0">
                    <td align="right" width="150" style="border:0">Catalog Value:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" name="catvalue" size="15" style="background-color:#FAD090" value="<?php echo $_POST['catvalue']?>" /></td>
              	</tr>
                <tr style="border:0">
                    <td align="right" width="150" style="border:0">Deductible Calculated:</td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" name="dedc" size="15" disabled="disabled" value="<?php echo number_format($dudc,2) ?>" /></td>
              	</tr>
                <tr style="border:0">
                    <td align="right" width="150" style="border:0">Deductible Manual: <input type="checkbox" name="cmded" <?php if (checkAgent()) echo 'disabled="disabled"'?> <?php if($_POST['cmded']) echo 'checked="checked"'?> /></td>
                    <td width="10" style="border:0">&nbsp;</td>
                    <td style="border:0"><input type="text" name="mdedc" style="background-color:#FAD090"  size="15" <?php if (checkAgent()) echo 'disabled="disabled"'?> value="<?php echo number_format($_POST['mdedc'],2) ?>" /> </td>
              	</tr>
           	</table>
        </td>
    </tr>
    <tr>
    	<td colspan="3" align="right"><input type="submit" value="Re-Calculate"/> &nbsp; <input type="submit" name="print" value="Print"/></td>
    </tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">&nbsp;</td></tr>
    <tr><td colspan="3">
    	<table width="100%" border="1">
        	<tr style="border:0">
                	<td colspan="3" align="center" style="border:0;color:#148540"><h3>Covernote</h3></td>
            </tr>
            <tr style="border:0"><td colspan="3" style="border:0">&nbsp;</td></tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Policy Number</td>
                <td colspan="2" style="border:0"><input type="text" name="pon" style="background-color:#FAD090"  size="20" value="<?php echo $_POST['pon']?>"/></td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Address</td>
                <td colspan="2" style="border:0"><input type="text" name="address" style="background-color:#FAD090"  size="60" value="<?php echo $_POST['address']?>"/></td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Payment</td>
                <td colspan="2" style="border:0"><input type="text" name="payment" style="background-color:#FAD090"  size="15" value="<?php echo number_format($_POST['payment'],2)?>"/></td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Make of Vehicle</td>
                <td colspan="2" style="border:0"><input type="text" name="make_vehicle" style="background-color:#FAD090"  size="20" value="<?php echo $_POST['make_vehicle']?>"/></td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Vehicle Year</td>
                <td colspan="2" style="border:0"><input type="text" name="year_vehicle" style="background-color:#FAD090"  size="4" maxlength="4" value="<?php echo $_POST['year_vehicle']?>"/></td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Registration No.</td>
                <td colspan="2" style="border:0"><input type="text" name="reg_mark" style="background-color:#FAD090"  size="20" value="<?php echo $_POST['reg_mark']?>"/></td>
            </tr>
             <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Chassis No.</td>
                <td colspan="2" style="border:0"><input type="text" name="chassis_no" style="background-color:#FAD090"  size="30" value="<?php echo $_POST['chassis_no']?>"/></td>
            </tr>
             <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Type</td>
                <td colspan="2" style="border:0"><input type="text" name="type" style="background-color:#FAD090"  size="20" value="<?php echo $_POST['type']?>"/></td>
            </tr>
             <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Color</td>
                <td colspan="2" style="border:0"><input type="text" name="color" style="background-color:#FAD090"  size="10" value="<?php echo $_POST['color']?>"/></td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Authorized Driver</td>
                <td colspan="2" style="border:0">1: <input type="text" name="auth1" style="background-color:#FAD090"  size="20" value="<?php echo $_POST['auth1']?>"/>&nbsp;2: <input type="text" name="auth2" style="background-color:#FAD090"  size="20" value="<?php echo $_POST['auth2']?>"/>&nbsp;3: <input type="text" name="auth3" style="background-color:#FAD090"  size="20" value="<?php echo $_POST['auth3']?>"/>&nbsp;Any: <input type="checkbox" name="authany" style="background-color:#FAD090" value="1" <?php if ($_POST['authany']){echo 'checked="checked"';}?>/></td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Assignee</td>
                <td colspan="2" style="border:0"><input type="text" name="assignee" style="background-color:#FAD090"  size="25" value="<?php echo $_POST['assignee']?>"/></td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Premium Payment</td>
                <td colspan="2" style="border:0">
                <?php
					require_once('support/calendar/tc_calendar.php');
                	$myCalendar = new tc_calendar("prem_date", true);
					$myCalendar->setIcon("support/calendar/images/iconCalendar.gif");
					list($year,$month,$day) = explode("-",$_POST['prem_date']);
					if( ($year!=date('Y') || $month!=date('m') || $day!=date('d')) && $_POST['date_from'] !== NULL){
						//Date NOT Change	
						$myCalendar->setDate($day,$month,$year);
					}
					else{
						$myCalendar->setDate(date('d'), date('m'), date('Y'));
					}
					$myCalendar->setPath("support/calendar/");
					$myCalendar->setYearInterval(date('Y'), date('Y')+3);
					//$myCalendar->setYearInterval('2011', '2013');
					$myCalendar->dateAllow(date('Y').'-'.(date('m')-1).'-'.date('d'), (date('Y')+2).'-'.(date('m')-1).'-'.date('d'));
					//$myCalendar->dateAllow('2008-05-13', '2015-03-01');
					$myCalendar->startMonday(true);
					$myCalendar->setAlignment('left', 'top');
					$myCalendar->writeScript();
				?>
                </td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Insurance From Date</td>
                <td colspan="2" style="border:0"><?php
					require_once('support/calendar/tc_calendar.php');
                	$myCalendar = new tc_calendar("date_from", true);
					$myCalendar->setIcon("support/calendar/images/iconCalendar.gif");
					list($year,$month,$day) = explode("-",$_POST['date_from']);
					if( ($year!=date('Y') || $month!=date('m') || $day!=date('d')) && $_POST['date_from'] !== NULL){
						//Date NOT Change	
						$myCalendar->setDate($day,$month,$year);
					}
					else{
						$myCalendar->setDate(date('d'), date('m'), date('Y'));
					}
					$myCalendar->setPath("support/calendar/");
					$myCalendar->setYearInterval(date('Y'), date('Y')+3);
					//$myCalendar->setYearInterval('2011', '2013');
					$myCalendar->dateAllow(date('Y').'-'.(date('m')-1).'-'.date('d'), (date('Y')+2).'-'.(date('m')-1).'-'.date('d'));
					//$myCalendar->dateAllow('2008-05-13', '2015-03-01');
					$myCalendar->startMonday(true);
					$myCalendar->setAlignment('left', 'top');
					$myCalendar->writeScript();
				?>
				</td>
            </tr>
            <tr>
            	<tr style="border:0">
            	<td width="150" style="border:0">Insurance to Date</td>
                <td colspan="2" style="border:0"><?php
					require_once('support/calendar/tc_calendar.php');
                	$myCalendar = new tc_calendar("date_to", true);
					$myCalendar->setIcon("support/calendar/images/iconCalendar.gif");
					list($yeart,$montht,$dayt) = explode("-",$_POST['date_to']);
					if( ($yeart!=date('Y') || $montht!=date('m')+1 || $dayt!=date('d')) && $_POST['date_to'] !== NULL){
						//Date NOT Change	
						$myCalendar->setDate($dayt,$montht,$yeart);
					}
					else{
						$myCalendar->setDate(date('d'), date('m')+1, date('Y'));
					}
					$myCalendar->setPath("support/calendar/");
					$myCalendar->setYearInterval(date('Y'), date('Y')+3);
					$myCalendar->dateAllow(date('Y').'-'.(date('m')-1).'-'.date('d'), (date('Y')+2).'-'.(date('m')-1).'-'.date('d'));
					$myCalendar->startMonday(true);
					$myCalendar->setAlignment('left', 'top');
					$myCalendar->writeScript();
				?>
				</td>
            </tr>
        </table>
        <input type="submit" name="print_covernote" value="Print Covernote"/>
    </td></tr>
</table>

</form>
</body>
</html>
