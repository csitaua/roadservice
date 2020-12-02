<?php

include 'dbc.php';
page_protect();
date_default_timezone_set('America/Aruba');
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
		if($_POST['BasicPremium'] == 305 || $_POST['BasicPremium'] == 480 || $_POST['BasicPremium'] == 174.6 || $_POST['BasicPremium'] == 268.35){
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
	$dudtp = 0;
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
			/*if(strcmp($country,'Bonaire')==0){
				$pdf->Cell($sp2,5,$currency.' '.number_format(84,2),0,0,'R');
			}
			else{
				$pdf->Cell($sp2,5,$currency.' '.number_format(150,2),0,0,'R');
			}*/
			$pdf->Cell($sp2,5,$currency.' '.number_format($dudtp,2),0,0,'R');
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
    	<td>Country: <?php echo $country.' ('.getLevel().')'?></td>
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
                    <td width="415" style="border:0"><input type="text" style="background-color:#FAD090" name="Other" size="50" value="<?php echo $_POST['Other']?>"/></td>
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
                    	<select name="BasicPremium" style="background-color:#FAD090; width:7em">
                        	<option value="<?php if(strcmp($country,'Bonaire')==0) {echo 174.60;} else {echo 305;}?>" <?php if ($br==305 || $br == 174.6){echo 'selected="selected"';}?>><?php if(strcmp($country,'Bonaire')==0) {echo number_format(174.6,2);} else {echo number_format(305,2);}?></option>
                            <option value="<?php if(strcmp($country,'Bonaire')==0) {echo 268.15;} else {echo 480;}?>" <?php if ($br==480 || $br == 268.15){echo 'selected="selected"';}?>><?php if(strcmp($country,'Bonaire')==0) {echo number_format(268.15,2);} else {echo number_format(480,2);}?></option>
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
</table>

</form>
</body>
</html>
