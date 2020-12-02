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
	
	$filter = '';
	 
	$pagelayout = array(216, 355.6); //ledger
	$pdf = new TCPDF('L', 'mm', $pagelayout, true, 'UTF-8', false);
	
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nagico Road Service');
	$pdf->SetTitle('Tow Report');
	$pdf->SetSubject('Tow Report');
	
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	$pdf->setAutoPageBreak(true,10);
	$pdf->SetFont('helvetica', 'B', 18);
	
	$pdf->AddPage();
	
	$pdf->Image('images/nagico-logo.jpg',10,10,65,0,'','','',true);
		
	$pdf->Cell(80,5,'');
	$aid = $_REQUEST['aid'];
	$sql2 = "SELECT * FROM `attendee` WHERE `id`='$aid'";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	$uid = $row2['user_id'];
	$sql2 = "SELECT * FROM `users` WHERE `id` = '$uid'";
	$rs2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($rs2);
	
	$pdf->Cell(0,5,'Commission Report for '.$row2['full_name']);
	$pdf->Ln(8);
	$pdf->Cell(80,5,'');
	$pdf->Cell(0,5,'From '.$sdate.' to '.$edate);
	$pdf->Ln(20);
	
	$sdate = $_REQUEST['sdate'];
	$edate = $_REQUEST['edate'];
	$aid = $_REQUEST['aid'];
	$sql = "SELECT * FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND `attendee_id` = '$aid' AND (`job`=3 OR `job`=12 OR `job`=13 OR `job`=14 OR `job`=20 OR `job`=22 OR `job`=17 OR `job`=29 OR `job`=33 OR `job`=32 OR `job`=36 OR `job`=19) AND `delete` = 0 AND (`status`=2 OR `status`=4 OR `status`=6 OR `status`=14 )".$filter;
	$rs = mysql_query($sql);
	$n = 1;
	$pdf->SetFont('helvetica', '', 10);
	$pdf->Cell(218,5,'');
	//$pdf->cell(20,5,'Insured');	
	//$pdf->Cell(0,5,'Commission');
	$pdf->Ln(5);
	$tot_com = 0;
	while($row = mysql_fetch_array($rs)){
		$pdf->Cell(8,5,$n);
		$n++;
		$pdf->cell(20,5,str_pad($row['id'],5,'0',STR_PAD_LEFT));
		$pdf->cell(25,5,$row['a_number']);
		
		$y = $pdf->gety();
		$x = $pdf->getx();
		$pdf->multicell(65,5,$row['location'],0,'L',false,2);
		$ya = $pdf->gety();
		$pdf->sety($y);
		$pdf->setx($x+65);
		$jid = $row['job'];
		$sql2 = "SELECT * FROM jobs WHERE id='$jid'";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->cell(40,5,$row2['description']);
		$extran = '';
		if($row['over_time']==1){
			$extran=' *';	
		}
		
		$odate = substr($row['opendt'],0,10);
		list($month,$day,$year) = explode("-",$odate);
		$dayn = date("N", mktime(0,0,0,intval($month),intval($day),intval($year)));
		$openh = intval(substr($row['opendt'],-5,2));
		if($dayn > 5 || $openh < 7 || $openh >= 18){
			$pdf->settextcolor(34,139,34);
		}
		$pdf->cell(40,5,$row['opendt'].$extran);
		$pdf->settextcolor(0,0,0);
		$tow_com = tow_com($row['opendt'],$row['insured'],$row['charged'],$row['job']);
		if ($row['status'] == 6 && $row['po_received']!=0){
			
		}
		else if( ($row['money_delivered'] == 0 && $row['charged'] != 0) || $row['status'] == 4){
			$extra = ' *';
			$tow_com = 0;
		}
		else{
			$extra = '';
		}
		
		$pdf->cell(23,5,'Afl. '.number_format($row['charged'],2).$extra);
		
		$jid = $row['status'];
		$sql2 = "SELECT * FROM status WHERE id='$jid'";
		$rs2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($rs2);
		$pdf->cell(40,5,$row2['status']);
		
		if($row['insured']){
			$pdf->cell(20,5,'Yes');	
		}
		else{
			$pdf->cell(20,5,'No');		
		}
		
		
		$pdf->Cell(0,5,'Afl. '.number_format($tow_com,2));
		$tot_com += $tow_com;
		$pdf->sety($ya);
	}
	
	$pdf->Ln(3);
	
	$pdf->Cell(235,5,'');
	$pdf->Cell(40,5,'Total Commission: ');
	$pdf->Cell(25,5,'Afl. '.number_format($tot_com,2),'T');
	
	$pdf->Output('CommissionReport.pdf', 'D');	
}

echo menu();
$col1 = 120;
$col2 = 275;

?>

<form name="m_report" action="" method="post">
	<table width="900">
    	<tr>
        	<td colspan="3" align="center" style="border:0;color:#148540"><h3>Report Tow Commission</h3></td>
        </tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">Starting Date:</td>
            <td width="<?php echo $col2;?>">
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
        	<td width="<?php echo $col1;?>">End Date:</td>
            <td width="<?php echo $col2;?>">
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
  	</tr>
     <tr>
            <td width="<?php echo $col1;?>">Attendee:</td>
            <td width="<?php echo $col2;?>"><select name="aid" style="background-color:#FAD090">
                <?php
                    $sql = "SELECT * FROM attendee WHERE  `active`=1 ORDER BY `attendee`.`s_name`";
                    $rs = mysql_query($sql);
                    while($row=mysql_fetch_array($rs)){
                        echo '<option value="'.$row[0].'">'.$row['s_name'].'</option>';	
                    }
                ?>
            </select>
            <td>&nbsp;</td>
  	</tr>
 	<tr>
    	<td colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
        <td>&nbsp;
        </td>
   	</tr>
    </table>
</form>

</body>
</html>