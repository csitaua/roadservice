<?php



define ("DB_HOST", "localhost"); // set database host
//define ("DB_USER", "web"); // set database user
//define ("DB_PASS","O&8Bd0&iq;A-"); // set database password
define ("DB_USER", "root"); // set database user
define ("DB_PASS",""); // set database passwor
define ("email_uinser","claims@nagico-abc.com");
if($_SESSION['country']==='Aruba'){
	define ("DB_NAME","roadservice"); // set database name
	define ("HOST_INSPRO", "181.41.56.22, 54050");
	define ("FOLDER", "../aru/");
	define ("SP_SITE", "https://nagicogroup.sharepoint.com/sites/arugen/Policies/Forms/AllItems.aspx?view=7&q=ClientNumberOWSTEXT%3A");
	define ("SP_SITE_POL", 	"https://nagicogroup.sharepoint.com/sites/arugen/Policies/Forms/AllItems.aspx?view=7&q=PolicyNumberOWSTEXT%3A");
	$serverName = "181.41.56.22:54050";
}
else if($_SESSION['country']==='Curacao'){
	define ("DB_NAME","roadservice_cur"); // set database name
	define ("HOST_INSPRO", "161.22.55.183");
	define ("FOLDER", "../cur/");
	$serverName = "161.22.55.183";
}

else{
	header("Location: login.php");
	exit();
}
define ("ENC_KEY", "~k+a1KG$-%%<'|`M"); //encryption key
define ("email_password","dn8tYqX3j8hHkjye");
define ("email_user","claims@nagico-abc.com");
define ("email_port","26");

if($_SESSION['testing']){
	define ("DB_NAME","roadservice_dev"); // set database name
}


?>
