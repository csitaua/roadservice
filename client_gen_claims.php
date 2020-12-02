<?php
	
	$ter="Sint Maarten";
	$host="192.168.5.24"; // Host name
	$db_username="web"; // Mysql username
	$db_password="O&8Bd0&iq;A-"; // Mysql password
	if($ter==='Sint Maarten'){
		define ("DB_NAME","roadservice_sxm"); // set database name
		define ("HOST_INSPRO", "192.168.5.103");
		define ("FOLDER", "sxm/");
		$serverName = "192.168.5.103"; 
	}
	include ('db-info.inc');
	$db1 = mysql_connect("$host", "$db_username", "$db_password")or die("cannot connect");
	mysql_select_db(DB_NAME, $db1)or die("unable to access database");
?>
<html>
<head>
    <title>Nagico General Claims</title>
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
                
                <form action="client_gen_claims_rec.php" enctype="multipart/form-data" method="post" name="genClaims" id="getClaims" >
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
                            <td width="28%" align="right" style="font-weight:bold">Date of Loss</td>
                            <td width="72%" >
                            <table width="100%">
                            <tr><td width="45%">
                             <input type="text" id="date_of_loss" name="date_of_loss" readonly value="09-06-2017"/ class="fill_in" size="15">
  <button id="date_of_loss_button">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#date_of_loss_button').click(
      function(e) {
        $('#date_of_loss').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y"}).focus();
        e.preventDefault();
      } );
  </script></td><td width="20%"><b>Territory</b></td><td><input type="text" size="10" readonly value="<?php echo $ter;?>"/></td></tr></table>
  
                            </td>
                        </tr>
                        <tr>
                            <td align="right"  style="font-weight:bold">Policy Number</td>
                            <td>&nbsp;<input name="pol" type="text" size="15" class="fill_in" required></td>
                        </tr>
                        <tr>
                            <td align="right"  style="font-weight:bold">Insured Name</td>
                            <td><table width="100%">
                            <tr><td width="45%"><input name="requestedBy" type="text" size="25" class="fill_in" required></td><td width="25%"><b>Phone</b></td><td><input name="addphone" type="text" style="width:100%"  class="fill_in" required></td></tr></table>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"  style="font-weight:bold">Insured Email</td>
                            <td><table width="100%">
                            <tr><td width="45%"><input name="contact_info_email" type="text" size="25" class="fill_in" required></td><td width="25%"><b>Second Phone</b></td><td><input name="other_contact_phone" type="text" style="width:100%" class="fill_in"></td></tr></table>
                            
                            </td>
                        </tr>
                         <tr>
                            <td align="right"  style="font-weight:bold">Location/Address</td>
                            <td>&nbsp;<input name="loc" type="text" style="width:100%" class="fill_in" required></td>
                        </tr>
                           <tr>
                            <td align="right"  style="font-weight:bold">Is the property covered by any other insurance?</td>
                            <td>&nbsp;<input name="other_ins_info" type="text" style="width:100%" class="fill_in" required></td>
                        </tr>
                        <tr>
                            <td align="right"  style="font-weight:bold">Disaster Type</td>
                            <td>
                             <select name="job" class="fill_in" onchange="display(this)">
							<?php
                                $jid = $row['job'];
                                $sql2 = "SELECT * FROM jobs_gc order by sort_order";
                                $rs2 = mysql_query($sql2);
                                while($row2=mysql_fetch_array($rs2)){
                                    if($jid == $row2['id']){
                                        echo '<option selected="selected" value="'.$row2['id'].'">'.$row2['description'].'</option>';	
                                    }
                                    else{
                                        echo '<option value="'.$row2['id'].'">'.$row2['description'].'</option>';	
                                    }
                                }
                            ?>
                      		</select>
                            
                            </td>
                        </tr>
                        <tr>
                            <td align="right"  style="font-weight:bold" valign="top">Damage Type</td>
                            <td>
                            <table width="100%">
                            <tr>
                                <td width="15%">Roof</td>
                                <td width="35%"><input type="text" name="roof_dam_info" class="fill_in" style=" width:100%"/></td>
                                <td width="15%">Walls</td>
                                <td width="35%"><input type="text" name="wall_dam_info" class="fill_in" style=" width:100%"/></td>
                            </tr>
                            <tr>
                                <td width="15%">Windows</td>
                                <td width="35%"><input type="text" name="window_dam_info" class="fill_in" style=" width:100%"/></td>
                                <td width="15%">Content</td>
                                <td width="35%"><input type="text" name="content_dam_info" class="fill_in" style=" width:100%"/></td>
                            </tr>  
                            <tr>
                                <td >Other</td><td colspan="3"><input type="text" class="fill_in" name="other_dam" id="other_dam" style="width:100%;" value=""/></td>
                            </tr>
                            </table>                            
                            </td>
                        </tr>
                        <tr>
                            <td align="right"  style="font-weight:bold" valign="top">Action Taken to Mitigate Damages</td>
                            <td><textarea name="mit_risk_info" style="width:100%" rows="3" class="fill_in"></textarea></td>
                        </tr>
                         
                         <tr>
                            <td align="right"  style="font-weight:bold" valign="top">Direction To Location</td>
                            <td><textarea name="direction" style="width:100%"  rows="2" class="fill_in"></textarea></td>
                        </tr>
                         <tr>
                            <td align="right"  style="font-weight:bold" valign="top">Provide additional details of the damages</td>
                            <td><textarea name="client_note" style="width:100%"  rows="6" class="fill_in"></textarea></td>
                        </tr>
                         <tr>
                            <td align="right"  style="font-weight:bold" valign="bottom">Upload Pictures / Documents</td>
                            <td>
                            <div id='Uploadcontainer'>
<input type='file' name='uploadfiles[]' class='uploadfile' />
</div>
<button id='extraUpload' onclick="return addAnother('Uploadcontainer')">Add more</button>
<script type='text/javascript'>
function addAnother(hookID)
{
    var hook = document.getElementById(hookID);
    var el      =   document.createElement('input');
    el.className    =   'uploadfile';
    el.setAttribute('type','file');
    el.setAttribute('name','uploadfiles[]');
    hook.appendChild(el);
    return false;
}
</script>
                         
                            </td>
                        </tr>
                        <tr>
                            <td align="right"  style="font-weight:bold" valign="bottom"></td>
                            <td><b>VERY IMPORTANT - FRAUDULENT AND EXAGGERATED CLAIMS</b><br/>
                            <br/>
                            The above answers to our questions will be the basis of consideration of your claim. You must ensure that all information is true and correct to the best of your knowledge and belief, and that all material facts have been disclosed. A material fact is one that is likely to influence us in the assessment or acceptance of this claim, or one that is likely to influence our consideration of cover under the terms of your policy. If you are in any doubt as to whether a fact is material, you must disclose it.<br/>
                            <br/>
                            FAILURE TO DO THIS MAY MEAN THAT YOUR POLICY BECOMES INVALID AND A CLAIM PAYMENT WILL NOT BE MADE.<br/>
                            <br/>
                            <b>DECLARATION</b><br/>
                            <br/>
                            By clicking the submit button I/We declare the foregoing particulars to be correct according to my information and belief. I/We understand that you may seek information from other insurers to check the answers I/we have provided.
                            </td>
                        </tr>
                        <tr>
                            <td align="right"  style="font-weight:bold" valign="bottom"></td>
                            <td><div class="g-recaptcha" data-sitekey="6LfxnTAUAAAAAMuN_zKZt_FOMdaZy0BGH0M7ueNo" data-callback="recaptchaCallback"></div></td>
                        </tr>
                        
                        <tr>
                            <td colspan="2">
                              
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" > <div align="center">
                                    <p>
                                        <input style="display:none" name="submit" type="submit" id="btnSubmit" value="Submit">
                                    </p>

                                </div></td>
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

