<?php

//include 'dbc.php';

$_SESSION['country']='Aruba';
include ('db-info.inc');
define ("DB_HOST", "192.168.5.24"); // set database host
define ("DB_USER", "web"); // set database user
define ("DB_PASS","O&8Bd0&iq;A-"); // set database password
define ("DB_NAME","roadservice"); // set database name

$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.1");
$db1= mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection.");
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database2".$_SESSION['country'].' z');

$sql="SELECT * FROM (SELECT * FROM `vehicle_total_history` order by id DESC LIMIT 14) as `da` ORDER BY id DESC";
$rs=mysql_query($sql);
$row=mysql_fetch_array($rs);
$ldate=mktime(0,0,0,substr($row['dateTime'],5,2),substr($row['dateTime'],8,2),substr($row['dateTime'],0,4));
$aruba_week_target=56;
$current_week=date("W",$ldate);
$p_week=$current_week-1;
$current_day=date("N",$ldate);
$current_amount=$row['amount'];
$current_ytd=$row['ytd_new'];
$current_target=$row['target'];
$netdif=0;
$ytddif=0;
$pcurrent_amount=0;
$pcurrent_ytd=0;
$pnetdif=0;
$pytddif=0;
$n=1;

while($current_day!=1 && $row=mysql_fetch_array($rs)){
	$cdate=mktime(0,0,0,substr($row['dateTime'],5,2),substr($row['dateTime'],8,2),substr($row['dateTime'],0,4));
	if(date("N",$cdate)==7 && $n){
		$netdif=$current_amount-$row['amount'];
		$ytddif=$current_ytd-$row['ytd_new'];
		$pcurrent_amount=$row['amount'];
		$pcurrent_ytd=$row['ytd_new'];
		$n=0;
	}
	elseif(!$n && date("N",$cdate)==1){
		$pnetdif=$pcurrent_amount-$row['amount'];
		$pytddif=$pcurrent_ytd-$row['ytd_new'];
	}	
}


?>
<html>
<head>
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Description", "Amount", { role: "style" } ],
        ["New", <?php echo $ytddif;?>, "#39e600"],
        ["Net", <?php echo $netdif;?>, "#2db300"],
        ["Target", <?php echo $aruba_week_target;?>, "#ffcc00"],
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Week <?php echo $current_week;?> Vehicle Target",
        width: 600,
        height: 300,
        bar: {groupWidth: "45%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("veh_week"));
      chart.draw(view, options);
  }
  </script>
  
   <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Description", "Amount", { role: "style" } ],
        ["New", <?php echo $pytddif;?>, "#39e600"],
        ["Net", <?php echo $pnetdif;?>, "#2db300"],
        ["Target", <?php echo $aruba_week_target;?>, "#ffcc00"],
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "           Week <?php echo $p_week;?> Vehicle Target",
        width: 600,
        height: 300,
        bar: {groupWidth: "45%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("pveh_week"));
      chart.draw(view, options);
  }
  </script>
  
  
  
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Description", "Amount", { role: "style" } ],
        ["New", <?php echo $current_ytd;?>, "#39e600"],
        ["Net", <?php echo $current_amount;?>, "#2db300"],
        ["Target", <?php echo $current_target;?>, "#ffcc00"],
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Year To Date Vehicle Target",
        width: 600,
        height: 300,
        bar: {groupWidth: "45%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("veh_total"));
      chart.draw(view, options);
  }
  </script>
 </head>
 <body>
<table width="1000px">
	<tr><td align="center" colspan="2">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">	
    <tr bgcolor="#018440" bordercolor="#018440">
    	<td width="2%" headers="45px">&nbsp;</td>
        <td width="25%" height="45px" bordercolor="#018440"><img height="30" src="nagico-logo-w.png"/></td>
        <td valign="middle" bordercolor="#018440"><span style="font-size:36px; font-weight:bold; font:Arial;" ></span></td>
   	</tr>
  	</table>
	</td>
</tr>
<tr>
    <td width="50%">
		<div id="veh_week" style="width: 600px; height: 300px;"></div>
	</td>
    <td width="50%" valign="top">
    	<div id="pveh_week" style="width: 600px; height: 300px;"></div>
    </td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
    <td width="50%">
		<div id="veh_total" style="width: 600px; height: 300px;"></div>
	</td>
    <td width="50%" valign="top">
    	
    </td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td colspan="2">&nbsp;</td>

</tr>
<tr><td align="center" colspan="2">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">	
    <tr bgcolor="#018440" bordercolor="#018440">
    	<td width="2%" headers="45px">&nbsp;</td>
        <td width="96%" height="45px" bordercolor="#018440" align="right"><img height="20" src="nagico-tag-w.png"/></td>
        <td width="2%" headers="45px">&nbsp;</td>
        <td valign="middle" bordercolor="#018440"><span style="font-size:36px; font-weight:bold; font:Arial;" ></span></td>
   	</tr>
  	</table>
	</td>
</tr>
</table>
</body>
</html>