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
	
	$job = $_REQUEST['job'];
	$filter = '';
	if($job != 0){
		$filter = $filter.' AND job = '.$job;		
	}
	
	$pdf = new TCPDF('L', 'mm', 'letter', true, 'UTF-8', false);
	
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
		
	$pdf->Cell(70,5,'');
	$pdf->Cell(50,5,'Service Call Amount by the Hour From '.$sdate.' To '.$edate);
	$pdf->Ln(15);
	$pdf->SetFont('helvetica', 'B', 14);
	if($job == 0){
		$jf = 'None';	
	}
	else{
		$sql = "SELECT * FROM jobs WHERE id='$job'";
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		$jf = $row['description'];	
	}
	$pdf->Cell(0,5,"Job Filter: ".$jf);
	$pdf->Ln(25);
	
	require_once ('jpgraph/jpgraph.php');
	require_once ('jpgraph/jpgraph_bar.php');
	
	
	$sdate = $_REQUEST['sdate'];
	$edate = $_REQUEST['edate'];
	
	$sql = "SELECT * FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND `delete` = 0".$filter;
	$rs = mysql_query($sql);
	$datay=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	while($row = mysql_fetch_array($rs)){
		$openh = intval(substr($row['opendt'],-5,2));
		//echo $openh.'<br/>';
		$datay[$openh]++;
	}
	 
	 
	// Create the graph. These two calls are always required
	$graph = new Graph(1800,1000);
	$graph->SetScale('textint');
	 
	// Add a drop shadow
	$graph->SetShadow();
	 
	// Adjust the margin a bit to make more room for titles
	$graph->SetMargin(40,30,20,40);
	$xlabel =array('12AM','1AM','2AM','3AM','4AM','5AM','6AM','7AM','8AM','9AM','10AM','11AM','12PM','1PM','2PM','3PM','4PM','5PM','6PM','7PM','8PM','9PM','10PM','11PM');
	$graph->xaxis->SetTickLabels($xlabel);
	 
	// Create a bar pot
	$bplot = new BarPlot($datay);
	 
	// Adjust fill color
	$bplot->SetFillColor('orange');
	$graph->Add($bplot);
	 
	// Setup the titles
	$graph->title->Set('Usage per hour'); 
	$graph->xaxis->title->Set('Hour');
	$graph->yaxis->title->Set('Amount Calls');
	 
	$graph->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	 
	// Display the graph
	unlink('temp/usage_temp.png');
	$graph->Stroke('temp/usage_temp.png');
	
	$pdf->Image('temp/usage_temp.png','','',265);
	
	$pdf->Output('DetailReport.pdf', 'D');	
}

echo menu();
$col1 = 120;
$col2 = 275;

?>

<form name="m_report" action="" method="post">
	<table width="900">
    	<tr>
        	<td colspan="3" align="center" style="border:0;color:#148540"><h3>Service Call Report amount by the hour over period</h3></td>
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
            <td>&nbsp;</td>
        </tr>
        </td><tr>
        <td width="<?php echo $col1;?>">Job:</td>
        <td width="<?php echo $col2;?>"><select name="job" style="background-color:#FAD090">
           <option value="0">All</option>
            <?php
				$sql = "SELECT * FROM jobs order by description ";
				$rs = mysql_query($sql);
				while($row=mysql_fetch_array($rs)){
					echo '<option value="'.$row['id'].'">'.$row['description'].'</option>';	
				}
			?>
        </select>
        <td>&nbsp;</td>
  	</td>
    
        <tr>
            <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
            <td>&nbsp;</td>
        </tr>
    </table>
</form>

</body>
</html>