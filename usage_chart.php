<?php // content="text/plain; charset=utf-8"
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');
include "support/connect.php";
include "support/function.php";

$sdate = $_REQUEST['sdate'];
$edate = $_REQUEST['edate'];

$sql = "SELECT * FROM `service_req` WHERE STR_TO_DATE(`opendt`,'%m-%d-%Y') >= STR_TO_DATE('$sdate','%m-%d-%Y') AND STR_TO_DATE(`opendt`,'%m-%d-%Y') <= STR_TO_DATE('$edate','%m-%d-%Y') AND `delete` = 0";
$rs = mysql_query($sql);
$datay=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
while($row = mysql_fetch_array($rs)){
	$openh = intval(substr($row['opendt'],-5,2));
	//echo $openh.'<br/>';
	$datay[$openh]++;
}
 
 
// Create the graph. These two calls are always required
$graph = new Graph(900,400);
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
$graph->Stroke();
?>