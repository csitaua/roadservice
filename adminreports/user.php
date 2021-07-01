<?php
include '../dbc.php';
include '../support/function.php';
include '../Controllers/db.php';

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
page_protect();

if(!checkAdmin()) {
	header("Location: login.php");
	exit();
}

require_once('../tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

		public function Header(){
			$height=6;
			$this->SetX(10);
			$this->SetY(5);
			$this->SetFont('helvetica', 'B', 12);
			$this->Cell(120,$height,COMPANY_NAME.' User Report');
			$this->Ln();
			$this->SetFont('helvetica', 'B', 8);
			$this->Cell(40,$height,'Full Name');
			$this->Cell(60,$height,'Email');
			$this->Cell(10,$height,'Level');
			$this->Cell(30,$height,'Date Created');
			$this->Cell(20,$height,'Disabled');

		}
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

	$db = new Controllers\db();

	$users = $db->query('SELECT * FROM `users`')->fetchAll();

	$height=6;

	$pdf = new MYPDF('P', 'mm', 'Letter', true, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('RS');
	$pdf->SetTitle('User Report');
	$pdf->SetSubject('User Report');

	$pdf->setPrintHeader(true);
	$pdf->setPrintFooter(true);
	$pdf->setAutoPageBreak(true,20);

	$pdf->AddPage();
	$pdf->SetMargins(10,20,10,true);

	$pdf->Ln($height);
	$pdf->Ln($height);

	foreach ($users as $user){
		$pdf->SetFont('helvetica', '', 8);
		$pdf->Cell(40,$height,$user['full_name']);
		$pdf->Cell(60,$height,$user['user_email']);
		$pdf->Cell(10,$height,$user['user_level']);
		$pdf->Cell(30,$height,$user['date']);
		$pdf->Cell(20,$height,$user['banned']);
		$pdf->Ln($height);
	}

	$pdf->Output('UserReport.pdf', 'D');


?>
