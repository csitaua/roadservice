<?php
	include 'dbc.php';
//	ini_set('display_errors', '1');
//	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	date_default_timezone_set('America/Aruba');
	page_protect();
	include "support/connect.php";
	include "support/function.php";
	if($_SESSION['user_level'] < 2){
		header('location:index.php');
	}
	require 'Controllers/S3RSObjectController.php';
	use Controllers\S3RSObject;
	$s3_ob = new S3RSObject();

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

			$path = FOLDER."drivers_license/";


			$fn = 1;
			$ext = end(explode('.', $_FILES["drv_license"]["name"]));
			$file_name = $_POST['licenseNo'].'.'.$ext;
			//$remote_file = $path.$_FILES["drv_license"]["name"];
			$remote_file = $path.$_POST['licenseNo'].'.'.$ext;
			echo $s3_ob->putS3Object($_FILES["drv_license"]["tmp_name"],'drivers_license/'.$_POST['licenseNo'].'.JPG',1200,false,85,true);

				/*
			include('support/simpleimage.php');
			$image = new SimpleImage();
			$image->load($_FILES["drv_license"]["tmp_name"]);
			$image->resizeToWidth(1000);
			$image->Save($remote_file);
			*/

		}

		$fl = $_POST['licenseNo'].".JPG";

		$sql="SELECT * FROM drivers_license WHERE id='$_POST[licenseNo]'";
		$rs = mysql_query($sql);
		if(mysql_num_rows($rs)!=0){
			if($_FILES['drv_license']['tmp_name']){
				$sql="UPDATE drivers_license SET loc='$fl' WHERE id='$_POST[licenseNo]'";
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
	$rs4 = mssql_query($sql4);
	$row4 = mssql_fetch_array($rs4);
	$name = $row4['Full_Name'];
	list($lname,$fname) = explode(";",$name);
	$address = $row4['Address1'];
	$homephone = $row4['HomePhone'];
	$mobile = $row4['MobilePhone'];
	$clientNo = $row4['ClientNo'];

	$sql4 = "SELECT * FROM Clients LEFT JOIN Countries as c ON String1=CountryCode WHERE ClientNo='".$clientNo."' ";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$rs4 = mssql_query($sql4);
	$row4 = mssql_fetch_array($rs4);
	$email = $row4['Email'];
	$pn = $row4['IdentNo'];
	$db = substr($row4['Date_Birth'],0,10);
	$licExp = substr($row4['LicenseExpDate'],0,10);
	$bplace = $row4['Country'];
	$gender = $row4['Sex'];
	$adm = $row4['String7'];
}
?>

<form name="driv_up" enctype="multipart/form-data" action="" method="post">
	<table width="1200" cellspacing="0">
    	 <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h3>Add Drivers License</h3></td>
        </tr>

        <tr><td colspan="5">&nbsp;</td></tr>
        <tr><td colspan="5"  class="top-child_h" style="color:#148540">License Information</td></tr>
        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Drivers License Number:</td>
            <td class="middle-none-child" width="<?php echo $row2;?>">
          		<input type="text" class="fill" name="licenseNo" id="licenseNo" value="<?php echo $_REQUEST['license'];?>" required="required"/>&nbsp;
                <div class="buttonwrapper" style=" display:block">
					<a class="squarebutton" onclick="search_drv()"><span>Search</span></a>
				</div>
           	</td>
            <td class="middle-none-child" width="<?php echo $space;?>">&nbsp;</td>
            <td class="middle-none-child" width="<?php echo $row1-$space;?>">&nbsp;</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" rowspan="8">
            	<?php
			$sql="SELECT * FROM drivers_license WHERE id='$_REQUEST[license]'";
			$rs = mysql_query($sql);
			if(mysql_num_rows($rs)!=0){
				$row = mysql_fetch_array($rs);
		?>

            <a target="_blank" href="<?php echo $s3_ob->getS3PresignedURL('drivers_license/'.$row['loc'],5,true); ?>"><img width="400" src="<?php echo $s3_ob->getS3PresignedURL('drivers_license/'.$row['loc'],1,false); ?>" /></a>
        <?php } ?>
            </td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Risk Factor:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" colspan="4">
            	<table width="180">
                	<tr>
                    	<td width="20%" style="background-color:#4DFF00" align="center"><input type="radio" name="risk" value="1" <?php if($row['risk']==1 || $row['risk']==0) echo 'checked="checked"';?>/> </td>
                        <td width="20%" style="background-color:#CCFF00" align="center"><input type="radio" name="risk" value="2" <?php if($row['risk']==2) echo 'checked="checked"';?>/></td>
                        <td width="20%" style="background-color:#FFFF00" align="center"><input type="radio" name="risk" value="3" <?php if($row['risk']==3) echo 'checked="checked"';?>/></td>
                        <td width="20%" style="background-color:#FFB300" align="center"><input type="radio" name="risk" value="4" <?php if($row['risk']==4) echo 'checked="checked"';?>/></td>
                        <td width="20%" style="background-color:#FF3300" align="center"><input type="radio" name="risk" value="5" <?php if($row['risk']==5) echo 'checked="checked"';?>/></td>
                   	</tr>
                    <tr>
                    	<td width="20%" align="center">1</td>
                        <td width="20%" align="center">2</td>
                        <td width="20%" align="center">3</td>
                        <td width="20%" align="center">4</td>
                        <td width="20%" align="center">5</td>
                   	</tr>
                </table>
           	</td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Persoons Number:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" colspan="4">
          		<input type="text" class="fill" name="persNo" value="<?php echo $row['persoonsNo'].$pn;?>" size="20" maxlength="15" required="required"/>
           	</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Adm. Number:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" colspan="4">
          		<input type="text" class="fill" name="admNo" value="<?php echo $row['admNo'].$adm;?>" size="20" maxlength="15" required="required"/>
           	</td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Expire Date:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" colspan="4">
         	<?php
            	require_once('support/calendar/classes/tc_calendar.php');
                $myCalendar = new tc_calendar("expireDate", true);
                $myCalendar->setIcon("support/calendar/images/iconCalendar.gif");
                list($year,$month,$day) = explode("-",$row['expireDate']);
                if( ($year!=date('Y') || $month!=date('m') || $day!=date('d')) && $row['expireDate'] !== NULL){
                  	$myCalendar->setDate($day,$month,$year);
                }
                else{
					 list($year,$month,$day) = explode("-",$licExp);
					if( ($year!=date('Y') || $month!=date('m') || $day!=date('d')) && $licExp !== ''){
						$myCalendar->setDate($day,$month,$year);
					}
					else{
                  	$myCalendar->setDate(date('d'), date('m'), date('Y'));
					}
                }
                $myCalendar->setPath("support/calendar/");
                $myCalendar->setYearInterval(date('Y')-1, date('Y')+10);
               	$myCalendar->dateAllow((date('Y')-1).'-'.(date('m')).'-'.date('d'), (date('Y')+10).'-'.(date('m')).'-'.date('d'));
                $myCalendar->startMonday(true);
                $myCalendar->setAlignment('left', 'top');
            	$myCalendar->writeScript();
            ?>
            </td>
       	</tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Category (Seperate by "-"):</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" colspan="4">
          		<input type="text" class="fill" name="category" value="<?php echo $row['category'];?>" size="20" maxlength="15" required="required"/>
           	</td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">First Name / Last Name:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" colspan="4">
          		<input type="text" class="fill" name="firstName" id="firstName" value="<?php echo $row['firstName'].$fname;?>" required="required" size="30" maxlength="85"/> / <input type="text" class="fill" name="lastName" id="lastName" value="<?php echo $row['lastName'].$lname;?>" required="required" size="30" maxlength="85"/>
           	</td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Email:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" colspan="4">
          		<input class="fill" type="email" name="email" value="<?php echo $row['email'].$email;?>" size="30" maxlength="75"/>
           	</td>
        </tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Address:</td>
            <td class="middle-none-child" width="<?php echo $row2;?>">
          		<input type="text" class="fill" name="address" id="address" value="<?php echo $row['address'].$address;?>" size="35" maxlength="85"/>
           	</td>
            <td class="middle-none-child" width="<?php echo $space;?>">&nbsp;</td>
            <td class="middle-none-child" width="<?php echo $row1-$space;?>">Notes:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>"><textarea style="background-color:#FAD090" name="notes" cols="40" rows="3"><?php echo $row['notes'];?></textarea></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Birthday:</td>
            <td class="middle-none-child" width="<?php echo $row2;?>">
         	<?php
            	require_once('support/calendar/classes/tc_calendar.php');
                $myCalendar = new tc_calendar("birthDay", true);
                $myCalendar->setIcon("support/calendar/images/iconCalendar.gif");
                list($year,$month,$day) = explode("-",$row['birthDay']);
                if( ($year!=date('Y') || $month!=date('m') || $day!=date('d')) && $row['birthDay'] !== NULL){
                  	$myCalendar->setDate($day,$month,$year);
                }
                else{
					list($year,$month,$day) = explode("-",$db);
					if( ($year!=date('Y') || $month!=date('m') || $day!=date('d')) && $db!==''){

						$myCalendar->setDate($day,$month,$year);
					}
					else{
                  		$myCalendar->setDate(date('d'), date('m'), date('Y'));
					}
                }
                $myCalendar->setPath("support/calendar/");
                $myCalendar->setYearInterval(date('Y')-90, date('Y')+1);
               	$myCalendar->dateAllow((date('Y')-90).'-'.(date('m')).'-'.date('d'), (date('Y')+1).'-'.(date('m')).'-'.date('d'));
                $myCalendar->startMonday(true);
                $myCalendar->setAlignment('left', 'bottom');
            	$myCalendar->writeScript();
            ?>
            </td>
             <td class="middle-none-child" width="<?php echo $space;?>">&nbsp;</td>
            <td class="middle-none-child" width="<?php echo $row1-$space;?>">Old Driver License:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>"><input class="fill" type="text" name="oldLicense" value="<?php echo $row['oldDriversLicense'];?>"/></td>
       	</tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Birth Place:</td>
            <td class="middle-none-child" width="<?php echo $row2;?>">
          		<input type="text" class="fill" name="birthPlace" value="<?php echo $row['birthPlace'].$bplace;?>" size="25" maxlength="50" required="required"/>
           	</td>
            <td class="middle-none-child" width="<?php echo $space;?>">&nbsp;</td>
            <td class="middle-none-child" width="<?php echo $row1-$space;?>">Linked Driver License:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>"><input class="fill" type="text" name="linkedLicense" value="<?php echo $row['linkedDriversLicense'];?>"/></td>
        </tr>
        <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Gender:</td>
            <td class="middle-none-child" width="<?php echo $row2;?>">
          		<select name="gender" class="fill">
                	<option <?php if (strcmp($row['gender'],'m')==0 || $gender==='M'){echo 'selected="selected"';}?> value="m">Male</option>
                    <option <?php if (strcmp($row['gender'],'f')==0 || $gender==='F'){echo 'selected="selected"';}?> value="f">Female</option>
                </select>
           	</td>
            <td class="middle-none-child" width="<?php echo $space;?>">&nbsp;</td>
            <td class="middle-none-child" width="<?php echo $row1-$space;?>">Open Balance:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>">Afl. XXXXX</td>
      	</tr>
         <tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Phone / Mobile:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" colspan="4">
          		<input type="tel" class="fill" name="phone" id="phone" value="<?php echo $row['phone'].$homephone;?>" size="20" maxlength="15"/> / <input type="tel" id="mobile" class="fill" name="mobile" value="<?php echo $row['mobile'].$mobile;?>" size="20" maxlength="15"/>
           	</td>
        </tr>
      	<tr>
        	<td class="middle-left-child" width="<?php echo $row1;?>">Drivers License:</td>
            <td class="middle-right-child" width="<?php echo $row2;?>" colspan="4">
          		<input type="file" class="fill" name="drv_license"/>
           	</td>
        </tr>
        <tr><td class="middle-left-child" colspan="2"><input type="submit" name="Submit" value="submit" />
			</td>
			<td class="middle-right-child" colspan="3"><?php
			if($e){
				echo '&nbsp;&nbsp;Copy From Client Extra Information: <input type="checkbox" name="e" id="e" onchange="copyAll()"/>';
			}
		?>
        </td></tr>
        <tr><td class="bottom-child" colspan="5">&nbsp;</td></tr>
        <tr><td colspan="5">&nbsp;</td></tr>
         <tr><td colspan="5"  class="top-child_h" style="color:#148540">Active Vehicles</td></tr>
         <?php
				$sql4 = "SELECT ve.LicPlateNo, ve.VehStatus
FROM [insproSQL].[dbo].VW_VEHICLE ve LEFT JOIN [insproSQL].[dbo].VW_CLIENTS cl ON ve.ClientNo = cl.ClientNo WHERE cl.LicenseNo = '".$_REQUEST['license']."' ORDER BY
				CASE	WHEN ve.VehStatus='A' THEN 1
						WHEN ve.VehStatus='L' THEN 2
						WHEN ve.VehStatus='C' THEN 3
				End
				, ve.Date_Effective DESC, ve.PolicyNo DESC";
				$rs4 =mssql_query($sql4);
				while($row4 = mssql_fetch_array($rs4)){
					echo '<tr><td class="middle-child" colspan="5">'.$row4['LicPlateNo'].' ('.$row4['VehStatus'].')</td></tr>';
				}
		 ?>

        <tr><td class="bottom-child" colspan="5">&nbsp;</td></tr>
   	</table>
</form>
<script type="text/javascript">
	function search_drv(){
		licensenumber = document.getElementById('licenseNo').value;
		if(document.getElementById('licenseNo').value.length != 0){
			window.open('ins_drivers_license.php?license='+licensenumber, '_self');
		}
	}

	function copyAll(){
		if( document.getElementById('e').checked){
			document.getElementById('lastName').value = "<?php echo $e_lname;?>";
			document.getElementById('firstName').value = "<?php echo $e_fname;?>";
			document.getElementById('address').value = "<?php echo $e_address;?>";
			document.getElementById('phone').value = "<?php echo $e_phone;?>";
			document.getElementById('mobile').value = "<?php echo $e_mobile;?>";
		}
		else{
			document.getElementById('lastName').value = "";
			document.getElementById('firstName').value = "";
			document.getElementById('address').value = "";
			document.getElementById('phone').value = "";
			document.getElementById('mobile').value = "";
		}
	}
</script>
</body>
</html>
