<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');
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
$col1=175;
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
        	<td colspan="5" align="center" class="top-child_h" style="border:0;color:#148540"><h3>Check Year/Day Value</h3></td>
        </tr>
        
        
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td class="top-child" colspan="5">&nbsp;</td></tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">New Value</td>
            <td colspan="4" class="middle-right-child"><input type="number" size="6" name="new_value" maxlength="6" value="<?php echo $_POST['new_value'];?>"/></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Year</td>
            <td colspan="4" class="middle-right-child"><input type="number" size="4" name="year" maxlength="4" value="<?php echo $_POST['year'];?>"/></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Manufacturing Date</td>
            <td colspan="4" class="middle-right-child">
            <input type="text" id="manu" name="manu" readonly/ size="13" value="<?php echo $_POST['manu'];?>"/>
              <button id="manubutton">
                <img src="anytime/calendar.png" alt="[calendar icon]"/>
              </button>
              <script>
                $('#manubutton').click(
                  function(e) {
                    $('#manu').AnyTime_noPicker().AnyTime_picker({format: "%Y-%m-%d"}).focus();
                    e.preventDefault();
                  } );
				</script>
            </td>
        </tr>
          <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Date of Loss</td>
            <td colspan="4" class="middle-right-child">
            <input type="text" id="dateloss" name="dateloss" readonly/ size="13" value="<?php echo $_POST['dateloss'];?>"/>
              <button id="datelossbutton">
                <img src="anytime/calendar.png" alt="[calendar icon]"/>
              </button>
              <script>
                $('#datelossbutton').click(
                  function(e) {
                    $('#dateloss').AnyTime_noPicker().AnyTime_picker({format: "%Y-%m-%d"}).focus();
                    e.preventDefault();
                  } );
				</script>
            </td>
        </tr>
        
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Vehicle Use</td>
            <td colspan="4" class="middle-right-child"><select name="vuse">
            	<option value="PR" <?php if($_POST['vuse']==='PR'){echo 'selected="selected"';} ?>>Private</option>
                <option value="CM"  <?php if($_POST['vuse']==='CM'){echo 'selected="selected"';} ?>>Commercial</option>
            </select>
            </td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Condition</td>
            <td colspan="4" class="middle-right-child"><select name="condition">
            	<option value="1" <?php if($_POST['condition']==1){echo 'selected="selected"';} ?>>Good</option>
                <option value="2"  <?php if($_POST['condition']==2){echo 'selected="selected"';} ?>>Poor</option>
            </select>
            </td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Year Value / Repair Limit</td>
            <td colspan="4" class="middle-right-child">
            	<?php 
					if($_POST['year']){
						$yv = dayValue($_POST['year'],$_POST['new_value'],$_POST['vuse'], $_POST['condition']);
						echo number_format($yv,2).' / '. number_format($yv*2/3,2).' / '.number_format($yv*1/3,2);
					}
				?>
            </td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $col1;?>">Day Value / Repair Limit</td>
            <td colspan="4" class="middle-right-child">
            	<?php 
					if($_POST['manu']){
						$dv = dayValueA($_POST['manu'],$_POST['dateloss'],$_POST['new_value'],$_POST['vuse'],$_POST['condition']);
						echo number_format($dv,2).' / '.number_format($dv*2/3,2).' / '.number_format($dv*1/3,2);		
						
					}
				?>
            </td>
        </tr>
        
        <tr>
        	<td class="bottom-left-child" width="<?php echo $col1;?>"><input type="submit" name="calculate" value="Calculate"/></td>
            <td colspan="4" class="bottom-right-child">&nbsp;</td>
        </tr>
   	</table>
</form>
</body>
</html>