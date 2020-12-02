<?php

include 'dbc.php';
page_protect();

$id = $_REQUEST['id'];
$country = $_REQUEST['country'];

if(strcmp($country,getCCountry())!=0 && !checkAdmin() && !checkABC()){ //Wrong Country
	header( 'Location: index.php?country='.getCCountry());		
}
else{
	### set a header to tell the browser what kind of file I'm about to send
	header("Content-type: application/pdf");
	### then read in the .pdf file
	if($id == 1){
		$pdfLocation = "forms/".$country."/NAGICO Motor Vehicle Change Form.pdf";
	}
	else if($id == 2){
		$pdfLocation = "forms/".$country."/NAGICO Motor Vehicle Insurance_Proposal Form.pdf";
	}
	$pdf = file_get_contents($pdfLocation);
	### and the file out to the browser
	echo $pdf;
}



?>