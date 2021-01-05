<?php
/********************** MYSETTINGS.PHP**************************
This updates user settings and password
************************************************************/
include 'dbc.php';
page_protect();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

$err = array();
$msg = array();

if($_POST['doUpdate'] == 'Update')
{


$rs_pwd = mysql_query("select pwd from users where id='$_SESSION[user_id]'");
list($old) = mysql_fetch_row($rs_pwd);
$old_salt = substr($old,0,9);
$pwd_old = $_POST['pwd_old'];

//check for old password in md5 format
	//print_r($pwd_old);

	if($old === PwdHash(filter($pwd_old),$old_salt) && checkPwdComp($_POST['pwd_new']))
	{
	$newsha1 = PwdHash($_POST['pwd_new']);
	mysql_query("update users set pwd='$newsha1' where id='$_SESSION[user_id]'");
	$msg[] = "Your new password is updated";
	//header("Location: mysettings.php?msg=Your new password is updated");
	}
	else if($old !== PwdHash(filter($pwd_old),$old_salt))
	{
	 $err[] = "Your old password is invalid";
	 //header("Location: mysettings.php?msg=Your old password is invalid");
	}
	else{
		$err[] = "Your password does not meet length or complexity";
	}

}

if($_POST['doSave'] == 'Save')
{
// Filter POST data for harmful code (sanitize)
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}


mysql_query("UPDATE users SET
			`full_name` = '$data[name]',
			`address` = '$data[address]',
			`tel` = '$data[tel]',
			`fax` = '$data[fax]',
			`country` = '$data[country]',
			`website` = '$data[web]'
			 WHERE id='$_SESSION[user_id]'
			") or die(mysql_error());

//header("Location: mysettings.php?msg=Profile Sucessfully saved");
$msg[] = "Profile Sucessfully saved";
 }

$rs_settings = mysql_query("select * from users where id='$_SESSION[user_id]'");
?>
<html>
<head>
<title>My Account Settings</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#myform").validate();
	 $("#pform").validate();
  });

	function checkPassword(form) {
    password1 = form.pwd_new.value;
  	password2 = form.pwd_new_c.value;

    if (password1 == ''){
      alert ("Please enter Password");
			return false;
		}
    else if (password2 == ''){
      alert ("Please enter confirm password");
			return false;
		}
    else if (password1 != password2) {
      alert ("\nPassword did not match: Please try again...")
      return false;
    }
    else{
      return true;
  	}
  }

  </script>
<link href="styles.css" rel="stylesheet" type="text/css">
<link href="css/tailwind.css" rel="stylesheet">
</head>

<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 p-4">
<header class="bg-white shadow">
		<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
			<h2 class="font-semibold text-2xl text-gray-800 leading-tight">
				 My Settings
		 </h2>
	 </div>
</header>
<div class="py-12">
<table class="table-fixed">
  <tr>
    <td class="w-1/5 align-top px-6 py-6">
			<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
					<h2 class="font-semibold text-lg text-gray-800 leading-tight">My Account</h2>
					<a class="underline text-base text-blue-600 hover:text-blue-800 visited:text-purple-600" href="./">Agent Calculator Home</a><br>
					<a class="underline text-base text-blue-600 hover:text-blue-800 visited:text-purple-600" href="myaccount.php">My Account</a><br>
					<a class="underline text-base text-blue-600 hover:text-blue-800 visited:text-purple-600" href="mysettings.php">Settings</a><br>
					<a class="underline text-base text-blue-600 hover:text-blue-800 visited:text-purple-600" href="logout.php">Logout </a>
					<?php
				if (checkAdmin()) {
				/*******************************END**************************/
				?>
				</br>
				<a class="underline text-base text-blue-600 hover:text-blue-800 visited:text-purple-600" href="admin.php">Admin CP </a>
			<?php } ?>
			</div>
		</td>
		<td class="w-4/5 align-top px-6 py-6">
			<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-6 py-6">
	      <table class="text-base table-fixed w-full">
					<p>
		        <?php
			if(!empty($err))  {
			   echo "<div class=\"msg\">";
			  foreach ($err as $e) {
			    echo "* Error - $e <br>";
			    }
			  echo "</div>";
			   }
			   if(!empty($msg))  {
			    echo "<div class=\"msg\">" . $msg[0] . "</div>";

			   }
			  ?>
		      </p>
					<p>Here you can make changes to your profile. Please note that you will
		        not be able to change your email which has been already registered.</p>
						<?php while ($row_settings = mysql_fetch_array($rs_settings)) {?>
				      <form action="mysettings.php" method="post" name="myform" id="myform">
				        <table class="text-base table-fixed w-2/3">
				          <tr>
										<td class="w-1/4">Your Name</td>
				            <td class="w-3/4"><input name="name" disabled type="text" id="name" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm" value="<?php echo $row_settings['full_name']; ?>">
				          </tr>
				          <tr>
				            <td colspan="2">
				              <textarea name="address" style="display:none" cols="40" rows="4" class="required" id="address"><?php echo $row_settings['address']; ?></textarea>
				            </td>
				          </tr>
				          <tr>
				            <td>Country</td>
				            <td><input name="country" type="text" disabled id="country" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm" value="<?php echo $row_settings['country']; ?>" ></td>
				          </tr>
				          <tr>
				            <td>User Name</td>
				            <td><input name="user_name" type="text" id="web2" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm" value="<?php echo $row_settings['user_name']; ?>" disabled></td>
				          </tr>
				          <tr>
				            <td>Email</td>
				            <td><input name="user_email" type="text" id="web3" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm" value="<?php echo $row_settings['user_email']; ?>" disabled></td>
				          </tr>
				        </table>
				        <p align="center">
				          <input name="doSave" type="submit" id="doSave" value="Save" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
				        </p>
				      </form>
					  <?php } ?>
						</br>
						<table class="text-base table-fixed w-2/3">
							<h3 class="titlehdr">Change Password</h3>
				      <p>If you want to change your password, please input your old and new password
				        to make changes.</p>
				      <form name="pform" id="pform" method="post" action="" onSubmit = "return checkPassword(this)">
				          <tr>
				            <td class="w-1/4">Old Password</td>
				            <td class="w-3/4"><input name="pwd_old" type="password" id="pwd_old" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm"></td>
				          </tr>
				          <tr>
				            <td>New Password</td>
				            <td><input name="pwd_new" type="password" id="pwd_new" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm"></td>
				          </tr>
									<tr>
				            <td>Confirm New Password</td>
				            <td><input name="pwd_new_c" type="password" id="pwd_new_c" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm"></td>
				          </tr>
				        <p>&nbsp; </p>

						</table>
						<p align="center">
							<input name="doUpdate" type="submit" id="doUpdate" value="Update" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
						</p>
						</form>
				</table>

			</div>
		</td>
  </tr>
  <tr>




      <p>&nbsp; </p>
      <p>&nbsp;</p>

      <p align="right">&nbsp; </p></td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</div>
</table>
</div>
</body>
</html>
