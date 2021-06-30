<?php

/************** PHP LOGIN SCRIPT V 2.3*********************

(c) Balakrishnan 2009. All Rights Reserved

Usage: This script can be used FREE of charge for any commercial or personal projects. Enjoy!

Limitations:

- This script cannot be sold.

- This script should have copyright notice intact. Dont remove it please...

- This script may not be provided for download except from its original site.

For further usage, please contact me.

***********************************************************/

//error_reporting(E_ALL);

//ini_set('display_errors', '1');

session_start();
session_regenerate_id (true);
session_save_path( realpath(dirname($_SERVER['DOCUMENT_ROOT']).'sesrr'));

$_SESSION['testing']=1;

if($_POST['location']==='Aruba'){

	$_SESSION['country']='Aruba';

}

else if($_POST['location']==='Sint Maarten'){

	$_SESSION['country']='Sint Maarten';

}

else if ($_POST['location']==='Curacao'){

	$_SESSION['country']='Curacao';

}

$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

require_once 'dbc.php';

$err = array();

foreach($_GET as $key => $value) {

	$get[$key] = filter($value); //get variables are filtered.

}

if ($_POST['doLogin']=='Login')

{

foreach($_POST as $key => $value) {

	$data[$key] = filter($value); // post variables are filtered

}

$user_email = $data['usr_email'];

$pass = $data['pwd'];

if (strpos($user_email,'@') === false) {

    $user_cond = "user_name='$user_email'";

} else {

      $user_cond = "user_email='$user_email'";



}



$result = mysqli_query("SELECT `id`,`pwd`,`full_name`,`approved`,`user_level` FROM users WHERE

           $user_cond

			AND `banned` = '0'

			") or die (mysqli_error());

$num = mysqli_num_rows($result);

  // Match row found with more than 1 results  - the user is authenticated.

    if ( $num > 0 ) {



	list($id,$pwd,$full_name,$approved,$user_level) = mysqli_fetch_row($result);



	if(!$approved) {

	//$msg = urlencode("Account not activated. Please check your email for activation code");

	$err[] = "Account not activated. Please check your email for activation code";



	//header("Location: login.php?msg=$msg");

	 //exit();

	 }



		//check against salt
	if ($pwd === PwdHash($pass,substr($pwd,0,9))) {

	if(empty($err)){

     // this sets session and logs user in

	 //session_save_path( realpath(dirname($_SERVER['DOCUMENT_ROOT']).'sesrr'));

       //session_start();

	   //session_regenerate_id (true); //prevent against session fixation attacks.

	   // this sets variables in the session

		$_SESSION['user_id']= $id;

		$_SESSION['user_name'] = $full_name;

		$_SESSION['user_level'] = $user_level;

		$_SESSION['testing'] = 1; // set database host

		$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

		if($_POST['location']==='Aruba'){

			$_SESSION['country']='Aruba';

		}

		else if($_POST['location']==='Sint Maarten'){

			$_SESSION['country']='Sint Maarten';

		}

		else{

			$_SESSION['country']='Curacao';

		}



		//update the timestamp and key for cookie

		$stamp = time();

		$ckey = GenKey();

		mysqli_query("update users set `ctime`='$stamp', `ckey` = '$ckey' where id='$id'") or die(mysqli_error());



		//set a cookie



	   if(isset($_POST['remember'])){

				  setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");

				  setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");

				  setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");

		   		   setcookie("testing",$_SESSION['testing'], time()+60*60*24*COOKIE_TIME_OUT, "/");

				   }

		  header("Location: index.php");

		 }

		}

		else

		{

		//$msg = urlencode("Invalid Login. Please try again with correct user email and password. ");

		$err[] = "Invalid Login. Please try again with correct user email and password.";

		//header("Location: login.php?msg=$msg");

		}

	} else {

		$err[] = "Error - Invalid login. No such user exists";

	  }

}





?>

<html>

<head>

<title>Nagico Road Service</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>

<script language="JavaScript" type="text/javascript" src="js/jquery.validate.js"></script>

  <script>

  $(document).ready(function(){

    $("#logForm").validate();

  });

  </script>

<link href="styles.css" rel="stylesheet" type="text/css">

</head>

<body>

<center>

<table align="center" width="900" border="0" cellspacing="0" cellpadding="5" class="main">

  <tr>

    <td width="100%" valign="top">



	  <p>

	  <?php

	  /******************** ERROR MESSAGES*************************************************

	  This code is to show error messages

	  **************************************************************************/

	  if(!empty($err))  {

	   echo "<div class=\"msg\">";

	  foreach ($err as $e) {

	    echo "$e <br>";

	    }

	  echo "</div>";

	   }

	  /******************************* END ********************************/

	  ?></p>
      <form action="login.php" method="post" name="logForm" id="logForm" >

        <table align="center" width="65%" border="0" cellpadding="0" cellspacing="0" class="loginform">

        	 <tr>

            <td colspan="2" style="background-image:url('images/bg-color.jpg'); background-repeat:repeat-y repeat-x">&nbsp;</td>

          </tr>

          <tr>

            <td colspan="2"><img src="images/nagico-rr.jpg"/></td>

          </tr>

          <tr>

            <td width="28%" style="background-color:#028543; color:#FFF; font-weight:bold; padding:10;" align="right">Username / Email</td>

            <td width="72%" style="background-color:#028543; color:#FFF"><input name="usr_email" type="text" class="required" id="txtbox" size="25"></td>

          </tr>

          <tr>

            <td style="background-color:#028543; color:#FFF; font-weight:bold; padding:10" align="right">Password</td>

            <td style="background-color:#028543; color:#FFF"><input name="pwd" type="password" class="required password" id="txtbox" size="25"></td>

          </tr>

          <tr>

            <td style="background-color:#028543; color:#FFF; font-weight:bold; padding:10" align="right">Location</td>

            <td style="background-color:#028543; color:#FFF">

            <select name="location" id="location">

				<option value="Aruba">Aruba</option>

                <option value="Curacao">Curacao</option>



            </select>

            </td>

          </tr>

          <tr>

            <td colspan="2" style="background-color:#028543; color:#FFF"><div align="center">

                <input name="remember" type="checkbox" id="remember" value="1">

                Remember me</div></td>

          </tr>

          <tr>

            <td colspan="2" style="background-color:#028543; color:#FFF"> <div align="center">

                <p>

                  <input name="doLogin" type="submit" id="doLogin3" value="Login">

                  </p>



              </div></td>

          </tr>

          <tr>

            <td colspan="2" style="background-image:url('images/bg-color.jpg'); background-repeat:repeat-y repeat-x">&nbsp;</td>

          </tr>

        </table>

        <div align="center"></div>

        <p align="center">&nbsp; </p>

      </form>

      <p>&nbsp;</p>



      </td>

  </tr>

  <tr>

    <td colspan="3">&nbsp;</td>

  </tr>

</table>

</center>

</body>

</html>
