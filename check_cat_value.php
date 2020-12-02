<?php
	include 'dbc.php';
	date_default_timezone_set('America/Aruba');
	page_protect();
	include "support/connect.php";
	include "support/function.php";
	if($_SESSION['user_level'] < 2){
		header('location:index.php');	
	}
	
	if(trim($_REQUEST[num])!== ''){
		// check extra
		$id = $_REQUEST['num'];
		$sql = "SELECT * FROM `non_client_extra` WHERE `id`='$id'";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs) != 0){
			$row = mysql_fetch_array($rs);
			$e_lname = $row[lname];
			$e_fname = $row[fname];
			$e_address = $row[address];
			$e_phone = $row[phone];
			$e_mobile = $row[mobile];
			$e = 1;
		}
	}
	
	if(strcmp(($_REQUEST['Submit']),'submit')==0 && strcmp(trim($_POST['licenseNo']),'')!=0 ){
		$fileName = $_FILES['drv_license']['name'];
		$tmpName  = $_FILES['drv_license']['tmp_name'];
		$fileSize = $_FILES['drv_license']['size'];
		$fileType = $_FILES['drv_license']['type'];
		$fp      = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);
		
		if(!get_magic_quotes_gpc())
		{
			$fileName = addslashes($fileName);
		}
	
		if (($_FILES["drv_license"]["type"] == "image/jpeg" || $_FILES["drv_license"]["type"] == "image/pjpeg" || $_FILES["drv_license"]["type"] == "image/gif" || $_FILES["drv_license"]["type"] == "image/x-png" || $_FILES["drv_license"]["type"] == "image/png")){
			
		$path = "drivers_license/";
		
		$fn = 1;
		$ext = end(explode('.', $_FILES["drv_license"]["name"]));
		$file_name = $_POST['licenseNo'].'.'.$ext;
		//$remote_file = $path.$_FILES["drv_license"]["name"];
		$remote_file = $path.$_POST['licenseNo'].'.'.$ext;
		
		
		include('support/simpleimage.php');
		$image = new SimpleImage();
		$image->load($_FILES["drv_license"]["tmp_name"]);
		$image->resizeToWidth(1000);
		$image->Save($remote_file);
			
		}
		
		$sql="SELECT * FROM drivers_license WHERE id='$_POST[licenseNo]'";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)!=0){
			if($_FILES['drv_license']['tmp_name']){
				$sql="UPDATE drivers_license SET loc='$remote_file' WHERE id='$_POST[licenseNo]'";
				mysql_query($sql);
			}
			
			$sql = "UPDATE drivers_license SET firstName='".mysql_real_escape_string($_POST['firstName'])."',lastName='".mysql_real_escape_string($_POST['lastName'])."', email='".mysql_real_escape_string($_POST['email'])."', address='".mysql_real_escape_string($_POST['address'])."', birthDay='".mysql_real_escape_string($_POST['birthDay'])."', birthPlace='".mysql_real_escape_string($_POST['birthPlace'])."', gender='".mysql_real_escape_string($_POST['gender'])."', phone='".mysql_real_escape_string($_POST['phone'])."', mobile='".mysql_real_escape_string($_POST['mobile'])."', expireDate='".mysql_real_escape_string($_POST['expireDate'])."', category='".mysql_real_escape_string($_POST['category'])."', persoonsNo='".mysql_real_escape_string($_POST['persNo'])."', risk='".mysql_real_escape_string($_POST['risk'])."', admNo='".mysql_real_escape_string($_POST['admNo'])."', notes='".$_POST['notes']."', oldDriversLicense='".$_POST['oldLicense']."', linkedDriversLicense='".$_POST['linkedLicense']."' WHERE id='$_POST[licenseNo]'";
			mysql_query($sql);
			header("location: ins_drivers_license.php?license=".$_POST['licenseNo']);
		}
		else{
			$sql="INSERT INTO drivers_license (id,loc, firstName, lastName, email, address, birthDay, birthPlace, gender, phone, mobile, expireDate, category, persoonsNo, risk, admNo) VALUES ('$_POST[licenseNo]','$remote_file', '".mysql_real_escape_string($_POST['firstName'])."', '".mysql_real_escape_string($_POST['lastName'])."', '".mysql_real_escape_string($_POST['email'])."', '".mysql_real_escape_string($_POST['address'])."', '".mysql_real_escape_string($_POST['birthDay'])."', '".mysql_real_escape_string($_POST['birthPlace'])."', '".mysql_real_escape_string($_POST['gender'])."', '".mysql_real_escape_string($_POST['phone'])."', '".mysql_real_escape_string($_POST['mobile'])."', '".mysql_real_escape_string($_POST['expireDate'])."', '".mysql_real_escape_string($_POST['category'])."', '".mysql_real_escape_string($_POST['persNo'])."', '".mysql_real_escape_string($_POST['risk'])."','".mysql_real_escape_string($_POST['admNo'])."')";
			mysql_query($sql);
		}
		if($_REQUEST['close']==1){
			echo '<script type="text/javascript">window.close();</script>';
		}
		else{
			//echo $sql.' '.mysql_error();
			header("location: ins_drivers_license.php?license=".$_POST['licenseNo']);
		}
	}
$row1 = 175;
$row2 = 425;
$space = 25;
$sql="SELECT * FROM drivers_license WHERE id='$_REQUEST[license]'";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
echo menu();
$fname = '';
$lname = '';
$address = '';
$homephone = '';
$mobile = '';
$email = '';
$pn = '';
$db = '';
$licExp='';
$bplace = '';
$gender = '';
$adm='';
if(strcmp(($_REQUEST['pol']),'')!=0 && mysql_num_rows($rs) < 1){
	//$sql4 = "SELECT * FROM vehicles_2 WHERE PolicyNo = '".$_REQUEST['pol']."'";
	$sql4 = "SELECT * FROM VW_VEHICLE WHERE PolicyNo LIKE '".$_REQUEST['pol']."' ORDER BY VehStatus, Date_Renewal DESC, PolicyNo DESC";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$rs4 = sqlsrv_query($conn,$sql4,$params,$options);
	$row4 = sqlsrv_fetch_array($rs4);
	$name = $row4['Full_Name'];
	list($lname,$fname) = explode(";",$name);
	$address = $row4['Address1'];
	$homephone = $row4['HomePhone'];
	$mobile = $row4['MobilePhone'];	
	$clientNo = $row4['ClientNo'];
	
	$sql4 = "SELECT * FROM Clients LEFT JOIN Countries as c ON String1=CountryCode WHERE ClientNo='".$clientNo."' ";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$rs4 = sqlsrv_query($conn,$sql4,$params,$options);
	$row4 = sqlsrv_fetch_array($rs4);
	$email = $row4['Email'];
	$pn = $row4['IdentNo'];
	$db = substr($row4['Date_Birth'],0,10);
	$licExp = substr($row4['LicenseExpDate'],0,10);
	$bplace = $row4['Country'];
	$gender = $row4['Sex'];
	$adm = $row4['String7'];
}
?>

<form name="check_value" enctype="multipart/form-data" action="" method="post">
	<table width="1200" cellspacing="0">
    	 <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h3>Check Year/Day Value</h3></td>
        </tr>
        
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td class="bottom-child" colspan="5">&nbsp;</td></tr>
   	</table>
</form>
</body>
</html>