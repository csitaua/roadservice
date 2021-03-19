<?php
//error_reporting(E_ALL);
 //ini_set('display_errors', 'on');
	include 'dbc.php';
	page_protect();


	//Clean Temp Folder
	foreach (glob("temp_zip\*") as $file) {
		unlink($file);
	}
    //Get the directory to zip
    $filename_no_ext= $_GET['dir'];

    // we deliver a zip file
  	header("Content-Type: archive/zip");

    // filename for the browser to save the zip file
    header("Content-Disposition: attachment; filename=$filename_no_ext".".zip");

    // get a tmp name for the .zip
    $tmp_zip = tempnam ('/var/www/htmldev/temp_zip','tmp').'.zip';
    //change directory so the zip file doesnt have a tree structure in it.
  	//chdir($_GET['dir']);


    // zip the stuff (dir and all in there) into the tmp_zip file
    //exec('zip '.$tmp_zip.' *');

	$zip = new ZipArchive;
	$zip->open($tmp_zip, ZipArchive::CREATE);
	foreach (glob($_GET['dir']."\*") as $file) {
		$zip->addFile($file);
	}
	$zip->close();

    // calc the length of the zip. it is needed for the progress bar of the browser
    $filesize = filesize($tmp_zip);
    header("Content-Length: $filesize");

    // deliver the zip file
   $fp = fopen("$tmp_zip","r");
   echo fpassthru($fp);

    // clean up the tmp zip file
    //unlink($tmp_zip);
?>
