<?php
include 'dbc.php';
page_protect();
header("Location: mysettings.php");
exit();

?>
<html>
<head>
<title>My Account</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link href="styles.css" rel="stylesheet" type="text/css">
<link href="css/tailwind.css" rel="stylesheet">
</head>

<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 p-4">
<table width="100%" border="0" cellspacing="0" cellpadding="5" class="main">
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="160" valign="top">
<?php
/*********************** MYACCOUNT MENU ****************************
This code shows my account menu only to logged in users.
Copy this code till END and place it in a new html or php where
you want to show myaccount options. This is only visible to logged in users
*******************************************************************/
if (isset($_SESSION['user_id'])) {?>
<div class="myaccount">
  <p><strong>My Account</strong></p>
  <a href="./">Road Service Home</a><br>
  <a href="mysettings.php">Settings</a><br>
    <a href="logout.php">Logout </a>

  <p>You can add more links here for users</p></div>
<?php }
if (checkAdmin()) {
/*******************************END**************************/
?>
      <p> <a href="admin.php">Admin CP </a></p>
	  <?php } ?>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="732" valign="top"><p>&nbsp;</p>
      <h3 class="titlehdr">Welcome <?php echo $_SESSION['user_name'];?></h3>
	  <?php
      if (isset($_GET['msg'])) {
	  echo "<div class=\"error\">$_GET[msg]</div>";
	  }

	  ?>
      <p>This is the my account page</p>


      </td>
    <td width="196" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
</div>
</body>
</html>
