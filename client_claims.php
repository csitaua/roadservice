<?php
	
	$ter="Sint Maarten";
	if($_POST['gen_claims']==='Submit General Claim'){
		header("Location: client_gen_claims.php");
		exit;
	}
	if($_POST['veh_claims']==='Submit Vehicle Claim'){
		header("Location: client_veh_claims.php");
		exit;
	}
?>
<html>
<head>
    <title>Nagico Claims</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="anytime/anytime.css" />
	<script src="anytime/jquery-1.6.4.min.js"></script>
    <script src="anytime/anytime.js"></script>
    <script language="javascript" src="support/calendar/calendar.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <style>
		body { font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro";}
		tr { font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro";}
		td { font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro";}
		.fill_in{ background-color:#CCD1D1;}
</style>
</head>

<center>
    <table align="center" width="1200" border="0" cellspacing="0" cellpadding="5" class="main" >
        <tr>
            <td width="100%" valign="top">
                
                <form action="" method="post" name="sub_claims" id="" >
                    <table align="center" width="65%" border="0" cellpadding="3" cellspacing="3" class="loginform">
                        <tr>
                            <td colspan="2" align="right"><div style="color:#000; font-weight:bold; font-size:28px">&nbsp;</div></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center" valign="top"><img src="images/nagico-logo-no-tag.jpg" width="400"/></td>
                        </tr>
                         <tr>
                            <td colspan="2" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="28%" align="right" style="font-weight:bold"></td>
                            <td width="72%" ><a href="client_gen_claims.php"><img src="images/s_gen_claim.png" width="180" height="45" /></a></td>
                        </tr>
                        <tr>
                            <td align="right"  style="font-weight:bold"></td>
                            <td><a href="client_veh_claims.php"><img src="images/s_veh_claim.png" width="180" height="45" /></a> </td>
                        </tr>
                       
                        
                        <tr>
                            <td colspan="2">
                              
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" ></td>
                        </tr>
                        <tr>
                            <td colspan="2">&nbsp;</td>
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
<script>
    function recaptchaCallback() {
        var btnSubmit = document.getElementById("btnSubmit");

        btnSubmit.style.display="inline";
    }
</script>
</html>

