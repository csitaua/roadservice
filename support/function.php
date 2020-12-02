<?php



define ("enc_key", "ki+devte");

require_once ($_SERVER["DOCUMENT_ROOT"] . '/dbc.php');

function menu_old(){

	$string = '<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<link rel="stylesheet" type="text/css" href="anytime/anytime.css" />

<script src="anytime/jquery-1.6.4.min.js"></script>

<script src="anytime/anytime.js"></script>

<script language="javascript" src="support/calendar/calendar.js"></script>

<title>Nagico Road and Claims Service</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<!-- <link rel="shortcut icon" type="image/x-icon" href="menu/CSS/dropdown/transitional/themes/nvidia.com/images/favicon.ico" /> -->

<link href="menu/helper.css" media="screen" rel="stylesheet" type="text/css" />

<link href="menu/css/dropdown/dropdown.css" media="all" rel="stylesheet" type="text/css" />

<link href="menu/default.advanced.css" media="all" rel="stylesheet" type="text/css" />

<script src="validator/gen_validatorv4.js" type="text/javascript"></script>

</head>

<body>



<!--

<h1><table width="1200">

	<tr>

		<td><img src="images/banner-right.jpg" width="1200" /></td>

	</tr>

</table>

</h1> -->

<br/>



<ul class="dropdown dropdown-horizontal">

	<li><a href="">Service Call</a>

		<ul>';

		if($_SESSION['user_level'] > VIEW_LEVEL){

			$string=$string.'<li><a href="new_sc.php">New Service Call</a></li>

			';

		}

	$string=$string.'<li><a href="/roadservice">Service Call List</a></li>

					<li><a href="new_rental.php">New Rental</a></li>

					<li><a href="rental_list.php">Rental List</a></li>';

	if(checkAdmin()){

		$string=$string.'<li><a href="">Black list</a>

			<ul>

				<li><a href="blacklist_view.php">View Black List</a></li>

				<li><a href="blacklist.php">Enter New Black List</a></li>

			</ul>

		</li>';

	}

	$string=$string.'<li><a href="ins_drivers_license.php">Add Drivers License</a></li></ul>

	</li>';

	if($_SESSION['user_level'] > EXTERNAL_LEVEL){

		$string = $string.'<li><a href="">Downloads</a>

			<ul>

				<li><a href="">Schedule</a>

					<ul>

						<li><a href="download.php?file=rrdocs/Rooster Nagico Jan 2012.pdf">Schedule January 2012</a></li>

						<li><a href="download.php?file=rrdocs/NagicoScheduleFebr2012.pdf">Schedule Febraury 2012</a></li>

						<li><a href="download.php?file=rrdocs/Nagico rooster Maart2012.pdf">Schedule March 2012</a></li>

						<li><a href="download.php?file=rrdocs/Nagico rooster April2012.pdf">Schedule April 2012</a></li>

					</ul>

				</li>

				<li><a href="download.php?file=rrdocs/insp.2011.2drsvehicle.pdf">Inspection 2Dr Vehicle</a></li>

				<li><a href="download.php?file=rrdocs/insp.2011.4drsvehicle.pdf">Inspection 4Dr Vehicle</a></li>

				<li><a href="download.php?file=rrdocs/insp.2011.2drs.pickup.pdf">Inspection 2Dr Pickup</a></li>

				<li><a href="download.php?file=rrdocs/insp.2011.4drs.pickup.pdf">Inspection 4Dr Pickup</a></li>

				<li><a href="download.php?file=rrdocs/insp.2011.SUV.pdf">Inspection SUV</a></li>

				<li><a href="download.php?file=rrdocs/aua_yacht_boat_survey.pdf">Yacht/Boat Survey</a></li>

					<li><a href="download.php?file=rrdocs/road service call method.pdf">Road Service Call Method</a></li>

			</ul>

		</li>';

	}

	if($_SESSION['user_level'] > EXTERNAL_LEVEL){

	$string = $string.'<li><a href="">Travel Insurance</a>

		<ul>';

		if($_SESSION['user_level'] > VIEW_LEVEL){

			$string = $string.'<li><a href="new_te.php">New Travel Emergency</a></li>';

		}

		$string = $string.'<li><a href="list_te.php">Travel Emergency List</a></li>';

		$string = $string.'</ul>

	</li>';

	}

	if($_SESSION['user_level'] > VIEW_LEVEL){

		$string = $string.'<li><a href="check_ins.php">Check Insurance</a></li>

	';

	}

	if($_SESSION['user_level'] >= POWER_LEVEL || checkView()){

		$string	= $string.'

		<li><a href="">Reports</a>

			<ul>

				<li><a href="summary_range.php">Date Range Report</a></li>

				<li><a href="summary_monthly.php">Montly Report</a></li>

				<li><a href="report_detail.php">Detailed Report</a></li>

				<li><a href="report_scphour.php">Service Call Report per Hour</a></li>

				<li><a href="top_req.php">Top 50 Users</a></li>

				<li><a href="report_towcom.php">Tow Commission Report</a></li>

				<li><a href="report_rental.php">Rental Report</a></li>

			</ul>

		</li>';

	}

	$string = $string.'<li><a href="myaccount.php">My Account</a></li>';



	if (checkAdmin()) {

		$string = $string.' <li><a href="admin.php">Admin CP </a>

		<ul><li><a href="">Attendee</a>

			<ul>

				<li><a href="attendee.php">View Attendee</a></li>

				<li><a href="add_attendee.php">Add Attendee</a></li>

			</ul>

		</li></ul>

		</li>';

	}



   	$string = $string.'



		<li><a href="logout.php">Logout</a></li>

	</ul>





<p>&nbsp;</p>

';



return $string;



}



function menu(){

	$string = '<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<link rel="stylesheet" type="text/css" href="anytime/anytime.css" />

<script src="anytime/jquery-1.6.4.min.js"></script>

<script src="anytime/anytime.js"></script>

<script language="javascript" src="support/calendar/calendar.js"></script>

<title>Nagico Road and Claims Service</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<!-- <link rel="shortcut icon" type="image/x-icon" href="menu/CSS/dropdown/transitional/themes/nvidia.com/images/favicon.ico" /> -->

<link href="menu/helper.css" media="screen" rel="stylesheet" type="text/css" />

<link href="menu/css/dropdown/dropdown.css" media="all" rel="stylesheet" type="text/css" />

<link href="menu/default.advanced.css" media="all" rel="stylesheet" type="text/css" />

<script src="validator/gen_validatorv4.js" type="text/javascript"></script>

</head>

<body>



<!--

<h1><table width="1200">

	<tr>

		<td><img src="images/banner-right.jpg" width="1200" /></td>

	</tr>

</table>

</h1> -->

<!--[START-LX0]--><script type="text/javascript" src="js/menu.js"></script><!--[END-LX0]-->





<p>&nbsp;</p>

';



return $string;



}



function convDollar($val){

	return (ceil( ($val/1.79)/10 )*10);

}



function monthName($num){

	if($num == 1){

		return 'January';

	}

	else if($num == 2){

		return 'February';

	}

	else if($num == 3){

		return 'March';

	}

	else if($num == 4){

		return 'April';

	}

	else if($num == 5){

		return 'May';

	}

	else if($num == 6){

		return 'June';

	}

	else if($num == 7){

		return 'July';

	}

	else if($num == 8){

		return 'August';

	}

	else if($num == 9){

		return 'September';

	}

	else if($num == 10){

		return 'October';

	}

	else if($num == 11){

		return 'November';

	}

	else if($num == 12){

		return 'December';

	}

	else{

		return 'please check';

	}

}



function tow_com($date,$insured,$charged,$job){



	$odate = substr($date,0,10);

	list($month,$day,$year) = explode("-",$odate);



	$dayn = date("N", mktime(0,0,0,intval($month),intval($day),intval($year)));



	$openh = intval(substr($date,-5,2));

	if( ($dayn > 5 || $openh < 7 || $openh >= 18)){ //weekend non work

		if( ($job == 3 || $job == 29 || $job == 20) && $insured){

			return 10;

		}

		else if($job == 22){

			return 10;

		}

		else if($charged >= 45 && $charged <100){

			return 10;

		}

		else if($charged >= 100 && $charged <150){

			return 15;

		}

		else if($charged >= 150){

			return 25;

		}

		else{

			return 0;

		}

	}

	else if( $job == 17){ //pullout

		if($charged >= 45 && $charged <100){

			return 10;

		}

		else if($charged >= 100 && $charged <150){

			return 15;

		}

		else if($charged >= 150){

			return 25;

		}

	}

	else if( (($job == 3 || $job == 29 || $job == 20) && !$insured) || $job == 22){

		return 10;

	}

	else{

		return 0;

	}

}



function vac_day($date){

	$day = substr($date,0,10);

	return false;

}



function encrypt($sData, $sKey){

    $sResult = '';

    for($i=0;$i<strlen($sData);$i++){

        $sChar    = substr($sData, $i, 1);

        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);

        $sChar    = chr(ord($sChar) + ord($sKeyChar));

        $sResult .= $sChar;

    }

    return encode_base64($sResult);

}



function decrypt($sData, $sKey){

    $sResult = '';

    $sData   = decode_base64($sData);

    for($i=0;$i<strlen($sData);$i++){

        $sChar    = substr($sData, $i, 1);

        $sKeyChar = substr($sKey, ($i % strlen($sKey)) - 1, 1);

        $sChar    = chr(ord($sChar) - ord($sKeyChar));

        $sResult .= $sChar;

    }

    return $sResult;

}





function encode_base64($sData){

    $sBase64 = base64_encode($sData);

    return strtr($sBase64, '+/', '-_');

}



function decode_base64($sData){

    $sBase64 = strtr($sData, '-_', '+/');

    return base64_decode($sBase64);

}



function partsDep($year,$fdate,$idate,$value,$vuse,$condition){

	//fdata is date of manufucter

	//idate is date of loss

	//year is year of vehicle

	if(trim($fdate)!==''){

		//Use manu date more

		return 	dayValueA($fdate,$idate,$value,$vuse,$condition);

	}

	else{

		return dayValue($year,$value,$vuse,$condition);

	}

}



function partsDep5($year,$fdate,$idate,$value){

	//fdata is date of manufucter

	//idate is date of loss

	//year is year of vehicle

	if(trim($fdate)!==''){

		//Use manu date more

		$dStart = new DateTime($fdate);

		$dEnd  = new DateTime($idate);

		$dDiff = $dStart->diff($dEnd);

		$years= $dDiff->days/365;

		$yearsn=floor($years);

		if($yearsn>10){

			$yearsn=10;

		}

		return	$value*(1-($yearsn*0.05));

	}

	else{

		$dyear=substr($idate,0,4);

		$yearsn=$year-$dyear;

		if($yearsn>10){

			$yearsn=10;

		}

		return	$value*(1-($yearsn*0.05));

	}

}



function dayValue($year,$value,$vuse,$condition){

	$c = date('Y');

	$dif = $c - $year;

	if($dif > 0){

		for($i = 0 ; $i < $dif ; $i++){

			if($i == 0){

				if($vuse==='PR'){

				$perc = 0.25;

				}

				else{

				$perc = 0.30;

				}

			}

			else if($i == 1){

				if($vuse==='PR'){

					$perc = 0.20;

				}

				else if($condition==2){

					$perc=0.25;

				}

				else{$perc=0.20;}

			}

			else if($i == 2){

				$perc = 0.15;

			}

			else{

				$perc = 0.10;

			}

			$value = $value * (1-$perc);

		}

	}

	if($vuse==='PR' && $condition==2){

		$value=$value*.95;

	}

	return $value;

}



function dayValueA($fdate,$idate,$value,$vuse,$condition){

	$condition=1;

	$dStart = new DateTime($fdate);

   	$dEnd  = new DateTime($idate);

	$dDiff = $dStart->diff($dEnd);

   	$years= $dDiff->days/365;

	$yearsn=floor($years);

	if($yearsn > 0){

		for($i = 0 ; $i < $yearsn ; $i++){

			if($i == 0){

				if($vuse==='PR'){

				$perc = 0.25;

				}

				else{

				$perc = 0.30;

				}

			}

			else if($i == 1){

				if($vuse==='PR'){

					$perc = 0.20;

				}

				//else if($condition==1){

				//	$perc=0.25;

				//}

				else{$perc=0.20;}

			}

			else if($i == 2){

				$perc = 0.15;

			}

			else{

				$perc = 0.10;

			}

			$value = $value * (1-$perc);

			$te=$i+1;

			if($te==(int)($yearsn)){

				$i +=2;

				if($i-1 == 0){

					if($vuse==='PR'){

						$perc = 0.25;

					}

					else{

						$perc = 0.30;

					}

				}

				else if($i-1 == 1){

					if($vuse==='PR'){

					$perc = 0.20;

					}

					else{$perc=0.20;}

				}

				else if($i-1 == 2){

					$perc = 0.15;

				}

				else{

					$perc = 0.10;

				}

				$t = $value * (1-$perc);

				$value = $value - (($value-$t)*($years-$yearsn));





			}

		} //end

	}

	else{

		//year == 0

		if($vuse==='PR'){

			$perc = 0.25;

		}

		else{

			$perc = 0.30;

		}

		$t = $value * (1-$perc);

		$value = $value - (($value-$t)*($years-$yearsn));

	}



	if($vuse==='PR' && $condition==2){

		$value=$value*.95;

	}

	return $value;

}



function getPolicyMadeBy($policyNo){

	//$serverName = "192.168.5.103";

	//$connectionInfo = array( "Database"=>"insproSQL" , "UID"=>"exportsa", "PWD"=>"nvsql2304@@",'ReturnDatesAsStrings'=>true);

	//$conn = sqlsrv_connect( $serverName, $connectionInfo);

	include_once('dbc.php');

	$sql = "SELECT * FROM [insproSQL].[dbo].[VW_POLICIES] WHERE PolicyNo='".$policyNo."'";

	$rs = mssql_query($sql);

	$row = mssql_fetch_array($rs);

	return substr($row['UserName'],7);

}



function genAgentPDFNotification($id){
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	$sql="SELECT * FROM `service_req` WHERE id=$id";

	$rs=mysql_query($sql);

	$row=mysql_fetch_array($rs);

	$height=5;

	$path=FOLDER.'rranotification/'.$id.'/';



	if($row['n_form']!=='' && $row['n_overview']!=='' && $row['n_damage_1']!=='' && $row['insured']==1 && $row['n_license_1']!=='' && !file_exists($path.str_replace('/','-',$row['pol']).'_Acc_Notification.pdf')){

		// Minimal req: Form, Overview Image, Client Damage Image, Client License Image, and is insured at Nagico

		mkdir(FOLDER.'rranotification/'.$id);



		$ext=substr($row['n_form'],strpos($row['n_form'],'.',5)+1);

		if($ext==="pdf" || $ext==="PDF"){

			copy($row['n_form'],$path.'form.pdf');

			//include('support/simpleimage.php');

			$im = new imagick();

			$im->setResolution(300,300);
			$im->readImage($path.'form.pdf');
			$im->setCompressionQuality(80);
			$im->resizeImage(1200,1200,Imagick::FILTER_LANCZOS,1, TRUE);
			$im->setImageFormat('jpeg');
			$im = $im->flattenImages();
			$im->writeImage($path.'form.jpeg');
			$im->clear();
			$im->destroy();



		}

		else{

			copy($row['n_form'],$path.'form.jpeg');

		}



		//$serverName = "192.168.5.103";

		//$connectionInfo = array( "Database"=>"insproSQL" , "UID"=>"exportsa", "PWD"=>"nvsql2304@@",'ReturnDatesAsStrings'=>true);

		//$conn = sqlsrv_connect( $serverName, $connectionInfo);

		include_once('dbc.php');

		$lic=$row['a_number'];

		$pol=$row['pol'];



		$sql2 = "SELECT * FROM VW_VEHICLE WHERE LicPlateNo = '$lic' AND PolicyNo LIKE '$pol' ORDER BY

						CASE	WHEN VehStatus='A' THEN 1

								WHEN VehStatus='L' THEN 2

								WHEN VehStatus='C' THEN 3

						End

						, Date_Renewal DESC, PolicyNo DESC";

		$params = array();

		$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

		$rs2 = mssql_query($sql2);

		$row2 = mssql_fetch_array($rs2);



		require_once('tcpdf/tcpdf.php');



		$pagelayout = array(216, 279.4);

		$pdf = new TCPDF('P', 'mm', $pagelayout, true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);

		$pdf->SetAuthor('Nagico Insurances');

		$pdf->SetTitle('Nagico Claim Notification');

		$pdf->setPrintHeader(false);

		$pdf->setPrintFooter(false);

		$pdf->setAutoPageBreak(false,0);



		$dl=substr($row['opendt'],0,10);

		list($m,$d,$y)=explode("-",$dl);



		$pdf->AddPage();

		$pdf->Image('images/RR_Notification_FrontPage.jpg',0,0,216,279.4,'','','',true,200);

		$pdf->SetFont('myriad', '', 18);

		$pdf->Ln(120);

		//$pdf->Cell(0,$height,'Accident Date',0,1,'C');

		$pdf->writeHTML('<span style="color:#808080">Accident Date</span>',true,false,true,false,'C');

		$pdf->writeHTML('<span style="color:#000000">'.date("F j, Y", mktime(0, 0, 0, $m, $d, $y)).'</span>',true,false,true,false,'C');

		$pdf->Ln(10);

		$pdf->writeHTML('<span style="color:#808080">Policy Number</span>',true,false,true,false,'C');

		$pdf->writeHTML('<span style="color:#000000">'.$row['pol'].'</span>',true,false,true,false,'C');

		$pdf->Ln(10);

		$pdf->writeHTML('<span style="color:#808080">Driver Name</span>',true,false,true,false,'C');

		$pdf->writeHTML('<span style="color:#000000">'.$row2['Full_Name'].'</span>',true,false,true,false,'C');

		$pdf->AddPage();

		$pdf->Image('images/RR_Notification_ContentPage.jpg',0,0,216,279.4,'','','',true,200);

		$pdf->SetY(93);

		$pdf->SetFont('myriad', '', 24);



		$pdf->Cell(10,15,'');$pdf->writeHTML('<span style="color:#037F42">Table of Contents</span>',true,false,true,false,'');

		$pdf->Ln(10);

		$pdf->SetFont('myriad', '', 16);

		//writeHTMLCell ($w, $h, $x, $y, $html=''

		$pdf->Cell(10,15,'');$pdf->writeHTMLCell(170,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Claim Form</span>');$pdf->writeHTMLCell(0,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">1</span>',0,1);

		$pdf->Cell(10,15,'');$pdf->writeHTMLCell(170,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Accident Overview Image </span>');$pdf->writeHTMLCell(0,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">2</span>',0,1);

		$pdf->Cell(10,15,'');$pdf->writeHTMLCell(170,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Insured and Third Party Damage Images</span>');$pdf->writeHTMLCell(0,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">3</span>',0,1);

		$pdf->Cell(10,15,'');$pdf->writeHTMLCell(170,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Insured Policy & Driver License</span>');$pdf->writeHTMLCell(0,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">4</span>',0,1);

		$pdf->Cell(10,15,'');$pdf->writeHTMLCell(170,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">Third Party Policy & Driver License</span>');$pdf->writeHTMLCell(0,15,$pdf->getX(),$pdf->getY(),'<span style="color:#808080">5</span>',0,1);

		$pdf->AddPage();

		$pdf->Image($path.'form.jpeg',10,10,0,259,'','','',true,150);

		$pdf->SetXY(0,-7);

		$pdf->SetFont('myriad', '', 10);

		$pdf->Cell(0,$height,'Page - 1',0,0,'R');

		$pdf->AddPage();

		$pdf->Image($row['n_overview'],20,10,0,119.7,'','','',true,150);

		$pdf->SetXY(0,-7);

		$pdf->SetFont('myriad', '', 10);

		$pdf->Cell(0,$height,'Page - 2',0,0,'R');

		$pdf->AddPage();

		$pdf->Image($row['n_damage_1'],20,10,0,119.7,'','','',true,150);

		if(file_exists($row['n_damage_2'])){

			$pdf->Image($row['n_damage_2'],20,139.7,0,119.7,'','','',true,150);

		}

		else{

			$pdf->SetXY(20,150);

			$pdf->SetFont('myriad', '', 16);

			$pdf->writeHTMLCell(0,15,$pdf->getX(),$pdf->getY(),'<span style="color:#037F42">Third Party Damage Image Missing</span>');

		}

		$pdf->SetXY(0,-7);

		$pdf->SetFont('myriad', '', 10);

		$pdf->Cell(0,$height,'Page - 3',0,0,'R');

		$pdf->AddPage();

		$pdf->Image($row['n_policy_1'],20,10,176,0,'','','',true,150);

		$pdf->Image($row['n_license_1'],20,150,0,80,'','','',true,150);

		$pdf->SetXY(0,-7);

		$pdf->SetFont('myriad', '', 10);

		$pdf->Cell(0,$height,'Page - 4',0,0,'R');

		$pdf->AddPage();

		if(file_exists($row['n_policy_2'])){

			$pdf->Image($row['n_policy_2'],20,10,176,0,'','','',true,150);

		}

		else{

			$pdf->SetXY(20,10);

			$pdf->SetFont('myriad', '', 16);

			$pdf->writeHTMLCell(0,15,$pdf->getX(),$pdf->getY(),'<span style="color:#037F42">Third Party Policy Missing</span>');

		}

		if(file_exists($row['n_license_2'])){

			$pdf->Image($row['n_license_2'],20,150,0,80,'','','',true);

		}

		else{

			$pdf->SetXY(20,150);

			$pdf->SetFont('myriad', '', 16);

			$pdf->writeHTMLCell(0,15,$pdf->getX(),$pdf->getY(),'<span style="color:#037F42">Third Party License Missing</span>');

		}

		$pdf->SetFont('myriad', '', 10);

		$pdf->SetXY(0,-7);

		$pdf->Cell(0,$height,'Page - 5',0,0,'R');

		$pdf->AddPage();

		$pdf->Image('images/RR_Notification_BackPage.jpg',0,0,216,279.4,'','','',true,200);



		$pol=str_replace('/','-',$row['pol']);
		$path	= '/var/www/'.substr($path,2);
		$pdf->Output($path.$pol.'_Acc_Notification.pdf', 'F');

		unlink($path.'form.jpeg');
		unlink($path.'form.pdf');

		$sql3="SELECT * FROM `claims_notification` WHERE id='".trim($row2['AgentCode'])."'";

		$rs3=mysql_query($sql3);



		$sql4="SELECT * FROM `claims_notification` WHERE id='".$row['pol']."'";

		$rs4=mysql_query($sql4);

		$row3=mysql_fetch_array($rs3);

		$row4=mysql_fetch_array($rs4);

		if( $row3 || $row4){



			require_once('phpmailer/PHPMailerAutoload.php');

			$mail = new PHPMailer();

			$mail->IsSMTP(); // we are going to use SMTP

			$mail->Host       = 'cscentral102.accountservergroup.com';      // setting GMail as our SMTP server

			$mail->Port		  = email_port;

			//$mail->SMTPSecure = 'tls';

			//$mail->SMTPDebug  = 3;

			$mail->SMTPAuth   = true; // enabled SMTP authentication

			$mail->Username   = email_user;  // user email address

			$mail->Password   = email_password;            // password in GMail

			$mail->SetFrom('claims@nagico-abc.com','Nagico Aruba Claims');

			$mail->AddReplyTo('claims.aruba@nagico.com','Nagico Aruba Claims');  //email address that receives the response

			$mail->Subject    = 'Accident Notification for Policy Number '.$row['pol'];

			$mail->IsHTML(true);

			$mail->AddAttachment($path.$pol.'_Acc_Notification.pdf',$pol.'_Acc_Notification.pdf');



			$bt='Dear Madam, Sir,

						</br>

						</br>

						Please find attached the accident notification report for your client.<br>

						<br>

						Policy Number: '.$row['pol'].'<br>

						Client Name: '.$row2['Full_Name'].'

						<br>

						<br>

						Regards,<br>

		<br>

						NAGICO Aruba N.V. (Claims Department)

						';



			$mail->Body       = $bt;

			//$mail->AddAddress($to, "Claims Aruba");



		 	//return $row3['email1'].trim($row2['AgentCode']).' / '.mysql_num_rows($rs3);

			if($row3['email1']!==''){

				$mail->AddAddress($row3['email1'],$row3['email1']);

			}

			if($row3['email2']!==''){

				$mail->AddAddress($row3['email2'],$row3['email2']);

			}

			if($row3['email3']!==''){

				$mail->AddAddress($row3['email3'],$row3['email3']);

			}

			if($row3['email4']!==''){

				$mail->AddAddress($row3['email4'],$row3['email4']);

			}

			if($row3['email5']!==''){

				$mail->AddAddress($row3['email5'],$row3['email5']);

			}

			if($row3['email6']!==''){

				$mail->AddAddress($row3['email6'],$row3['email6']);

			}

			if($row4['email1']!==''){

				$mail->AddAddress($row4['email1'],$row4['email1']);

			}

			if($row4['email2']!==''){

				$mail->AddAddress($row4['email2'],$row4['email2']);

			}

			if($row4['email3']!==''){

				$mail->AddAddress($row4['email3'],$row4['email3']);

			}

			if($row4['email4']!==''){

				$mail->AddAddress($row4['email4'],$row4['email4']);

			}

			if($row4['email5']!==''){

				$mail->AddAddress($row4['email5'],$row4['email5']);

			}

			if($row4['email6']!==''){

				$mail->AddAddress($row4['email6'],$row4['email6']);

			}

			$mail->AddAddress('claims.aruba@nagico.com','Claims Aruba');
			$mail->AddAddress('gilbert.hooyboer@nagico.com','Gilbert Hooyboer');


			if($mail->Send()){

				//Email Send



			}

		}


	}

	else if($row['n_policy_1']!=='' && $row['n_license_1']!=='' && $row['job']==50 && $row['ref_not']==0){

		//email referal



		$sql5="SELECT * FROM attendee WHERE id='".$row['attendee_id']."'";

		$rs5=mysql_query($sql5);

		$row5=mysql_fetch_array($rs5);



		require_once('phpmailer/PHPMailerAutoload.php');

			/*$mail = new PHPMailer();

			$mail->IsSMTP(); // we are going to use SMTP

			$mail->Host       = 'mail.nagico-abc.com';      // setting GMail as our SMTP server

			$mail->Port		  = email_port;

			//$mail->SMTPSecure = 'tls';

			//$mail->SMTPDebug  = 1;

			$mail->SMTPAuth   = true; // enabled SMTP authentication

			$mail->Username   = email_user;  // user email address

			$mail->Password   = email_password;            // password in GMail

			$mail->SetFrom('claims@nagico-abc.com','Nagico Aruba Road Service');

			$mail->AddReplyTo('noreply@nagico.com','Nagico Aruba  Road Service');  //email address that receives the response */



			$mail = new PHPMailer();

			$mail->IsSMTP(); // we are going to use SMTP

			$mail->SMTPDebug  = 2;

			$mail->Host       = 'cs7-dallas.accountservergroup.com';      // setting GMail as our SMTP server

			$mail->Port		  = '465';

			$mail->SMTPSecure = 'tls';

			$mail->SMTPAuth   = true; // enabled SMTP authentication

			$mail->Username   = email_user;  // user email address

			$mail->Password   = email_password;            // password in GMail

			$mail->AddReplyTo($from,'1');  //email address that receives the response

			$mail->SetFrom('claims@nagico-abc.com','claims@nagico-abc.com');



			$mail->Subject    = 'New Client Referral '.$row['a_number'];

			$mail->IsHTML(true);

			$mail->AddAttachment($row['n_policy_1'],'Policy.jpg');

			$mail->AddAttachment($row['n_license_1'],'License.jpg');

			$bt='Dear Team,

						</br>

						</br>

						Please find attached the the policy and driver license.

						<br>

						<br>

						RS Attendee: '.$row5['s_name'].'<br>

						Link: <a href="https://roadservice.nagico-abc.com/roadservice/edit_sc.php?sc='.$id.'">New Client Call</a><br>

<br>

<br>

Regards,<br>

Road Service

						';



			$mail->Body       = $bt;





			//$mail->AddAddress('luis.ras@nagico.com','Luis Ras');

			$mail->AddAddress('Annique.Wever@NAGICO.COM','Annique Wever');

			$mail->AddAddress('Cefrenne.Koolman-Arendsz@NAGICO.COM','Cefrenne Koolman');

			$mail->AddAddress('gilbert.hooyboer@nagico.com','Gilbert Hooyboer');

			$mail->AddAddress('kenrick.kelly@nagico.com','kenrick.kelly@nagico.com');

			if($mail->Send()){

				//mark

				$sql5="UPDATE `service_req` SET `ref_not`=1 WHERE `id`=$id";

				mysql_query($sql5);

			}

	}

}



function time_loss_rate($country){


	$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$sql="SELECT * FROM country_info WHERE country='".$country."'";

	$rs=$db2->query($sql);

	$row=$rs->fetch_assoc();

	$rate=$row['time_loss_rate'];

	//$connection->close();

	return $rate;

}

function get_country_email($country){
	$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$sql="SELECT * FROM country_info WHERE country='".$country."'";
	$rs=$db2->query($sql);
	$row=$rs->fetch_assoc();
	$email=$row['email'];
	return $email;
}



function get_job_type($jobid){

	$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$sql="SELECT * FROM `jobs` WHERE `id`=".$jobid;
	$rs=$db2->query($sql);
	$row=$rs->fetch_assoc();
	$jobType=$row['jobs_group_id'];
	//$connection->close();

	return $jobType;

}



function isTowing($jobid){

	if(get_job_type($jobid)==5){

		return true;

	}

	else {

		return false;

	}

}



function isAcc($jobid){

	if(get_job_type($jobid)==7){

		return true;

	}

	else {

		return false;

	}

}



function isPropertyDamage($jobid){

	if(get_job_type($jobid)==6){

		return true;

	}

	else {

		return false;

	}

}



function isThirdPartyRental($rental_company_id){

	//$connection= new mysqli(DB_HOST, DB_USER, DB_PASS,DB_NAME);

	//if ($connection->connect_error) {

    //	die("Connection failed: ");

	//}
	$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$sql="SELECT * FROM `rental_company` WHERE `id`=".$rental_company_id;

	$rs=$db2->query($sql);

	$row=$rs->fetch_assoc();

	$isTP=$row['third_party'];

	//$connection->close();

	return $isTP;

}



function allowAttendeeStatusChange($id){


	$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$sql="SELECT * FROM `status` WHERE `id`=".$id;
	$rs=$db2->query($sql);
	$row=$rs->fetch_assoc();
	$allow=$row['att_change'];
	return $allow;

}



function allowJobGroupStatusChange($id){

	//$connection= new mysqli(DB_HOST, DB_USER, DB_PASS,DB_NAME);

	//if ($connection->connect_error) {

    //	die("Connection failed: ");

	//}


	$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$sql="SELECT * FROM `jobs` WHERE `id`=".$id;

	$rs=$db2->query($sql);

	$row=$rs->fetch_assoc();

	$job_group_id=$row['jobs_group_id'];



	$sql="SELECT * FROM `jobs_group` WHERE `id`=".$job_group_id;

	$rs=$db2->query($sql);

	$row=$rs->fetch_assoc();

	$closed_status_change=$row['closed_status_change'];



	//$connection->close();

	return $closed_status_change;

}



function getStatusGroupPermission($id){

	//$connection= new mysqli(DB_HOST, DB_USER, DB_PASS,DB_NAME);

	//if ($connection->connect_error) {

    //	die("Connection failed: ");

	//}
	$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$sql="SELECT * FROM `status` WHERE `id`=".$id;

	$rs=$db2->query($sql);

	$row=$rs->fetch_assoc();

	$perm=$row['status_group_permission'];

	//$connection->close();

	return $perm;

}

function recordLog($page, $id, $query){
	$uid=$_SESSION['user_id'];
	$uname=getUserFNameID($uid);
	$db2 = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$sql="INSERT INTO `log` (`users_id`,`users_name`,`page`,`log`,`keypageid`) VALUES ($uid,'$uname','$page','".mysql_real_escape_string($query)."','$id')";
	$rs=$db2->query($sql);
	return true;
}





?>
