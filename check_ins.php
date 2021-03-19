<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include 'dbc.php';
date_default_timezone_set('America/Aruba');

page_protect();

include "support/function.php";

if($_SESSION['user_level'] < RR_LEVEL){

	header("Location: index.php");

	exit();

}


session_start();



$policy=$_REQUEST['pn'];
$lic = $_REQUEST['lic'];

if($_SESSION['country']==='Aruba'){
	$licc=str_replace("-","",$lic);
}
else{
	$licc=$lic;
}

$name= $_REQUEST['name_lic'];



echo menu();

$col1 = 175;

$col2 = 250;



?>

	<table width="1200" cellspacing="0">

    	<tr>

        	<td colspan="5" align="center"><h8 style="color:#FFF">Check Insurance Status and Service History</h8></td>

        </tr>

        <tr><td colspan="5">&nbsp;</td></tr>

        <form name="check_ins" action="check_ins.php" method="post">

        <tr>

        	<td class="top-left-child" width="<?php echo $col1?>">Enter License Plate:</td>

           	<td class="top-right-child" width="<?php echo $col2?>"><input style="background-color:#FAD090" type="text" name="lic" value="<?php echo $_REQUEST['lic']?>"></td>

            <td colspan="3">&nbsp;</td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Enter Name:</td>

           	<td class="middle-right-child" width="<?php echo $col2?>"><input style="background-color:#FAD090" type="text" name="name_lic" value="<?php
					if(isset($_REQUEST['name_lic']))
					   {
							echo $_REQUEST['name_lic'];
					}
				?>"></td>

            <td colspan="3">&nbsp;</td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">License Number:</td>

           	<td class="middle-right-child" width="<?php echo $col2?>"><input style="background-color:#FAD090" type="text" name="license_no" value="<?php echo $_REQUEST['license_no']?>"></td>

            <td colspan="3">&nbsp;</td>

        </tr>

        <tr>

        	<td class="bottom-child" colspan="2"><input type="submit" name="submit" value="Submit"></td>

            <td colspan="3">&nbsp;</td>

        </tr>

        </form>

       <tr><td colspan="5">&nbsp;</td></tr>

    <tr>

    	<?php if(isset($_REQUEST['submit'])){?>

       	<td colspan="2" class="top-child_h" style="color:#148540;"><h4>Comments</h4></td>

        <?php }

		else{

         	echo '<td colspan="2">&nbsp;</td>';

		}

				$extra='';

				if(trim($policy)!==''){

					$extra=" AND PolicyNo='$policy' ";

				}





				$sql2 = "SELECT * FROM drivers_license WHERE id = '$lic'";

				$rs2 = mysql_query($sql2);

				if(trim($lic)!==''){

				$sql4 = "SELECT * FROM VW_VEHICLE WHERE (LicPlateNo = '$lic' or LicPlateNo = '$licc')  ".$extra." ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

				}

				else if(trim($name)!==''){

					$sql4 = "SELECT * FROM VW_VEHICLE WHERE Full_Name LIKE '%$name%' ".$extra." ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

				}

				else if(trim($_POST['license_no']!=='')){

					$sql4 = "SELECT * FROM VW_VEHICLE WHERE Driver1_License= '".$_POST['license_no']."' ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";



				}

				$rs4 = mssql_query($sql4);
				if($row4 = mssql_fetch_array($rs4)){
					$license = $row4['Driver1_License'];
					$clientNo = $row4['ClientNo'];
				}
				$rowe=mssql_fetch_array($rs4);
				$e='';

				if($rowe['VehStatus']=='A'){

					$e= '<a href="check_ins.php?pn='.$rowe['PolicyNo'].'&lic='.$lic.'"><span style="color:RED">Multiple Active Policies: '.$rowe['Full_Name'].'</span></a>';

				}



				if(trim($license)===''){

					$sql11="SELECT * FROM VW_CLIENTS

  WHERE ClientNo='$clientNo'";

  					$rs11=mssql_query($sql11);

					$row11=mssql_fetch_array($rs11);

					$license=$row11['LicenseNo'];

				}

				$dr_found = 0;


					$sql2 = "SELECT * FROM drivers_license WHERE id = '$license'";

					$rs2 = mysql_query($sql2);

					if($row3 = mysql_fetch_array($rs2)){

						$dr_found = 1;

					}

				if(trim($lic)!==''){



				$sql = "SELECT * FROM VW_VEHICLE WHERE (LicPlateNo = '$lic' or LicPlateNo = '$licc') ".$extra." ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

				}

				else if(trim($name)!==''){

					$sql = "SELECT * FROM VW_VEHICLE WHERE Full_Name LIKE '%$name%' ".$extra." ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

				}

				else if(trim($_POST['license_no']!=='')){

					$sql = "SELECT * FROM VW_VEHICLE WHERE Driver1_License= '".$_POST['license_no']."' ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

				}

				$rs = mssql_query($sql);

				$row = mssql_fetch_assoc($rs);



				$poli = $row['PolicyNo'];

				$sql5 = "SELECT * FROM license_policy WHERE policyNo ='$poli'";

				$rs5 = mysql_query($sql5);

				$row5 = mysql_fetch_array($rs5);

				$license2 = $row5['licenseNo'];

				$dr_found2 = 0;

				$sql6 = "SELECT * FROM drivers_license WHERE id = '$license2'";

				$rs6 = mysql_query($sql6);

				if($row6 = mysql_fetch_array($rs6)){

					$dr_found2 = 1;

				}

				if($dr_found == 1 && (trim($name)!=='' || trim($lic)!=='')){

			?>



            <td rowspan="20" valign="top">

            	<table width="100%" cellspacing="0">

                <tr>

                	<td width="10">&nbsp;</td>

                	<td class="top-child_h" style="color:#148540;"><h4>Drivers License Owner</h4></td>

                </tr>

                <tr>

                	<td width="10">&nbsp;</td>

                	<td class="bottom-child">

                    <table width="100%">

                    	<tr>

                        	<td width="50"><a target="_blank" href="download.php?file=<?php echo $row3['loc'];?>"><img width="300" src="download.php?file=<?php echo $row3['loc'];?>" /></a> <br/><span style="color:#C60; font-weight:bold">License Expiration Date: <span style="font-size:18px"><?php echo $row3['expireDate'];?></span></span></td>

                          <td width="50%" valign="top">

                            	<b>Risk Factor</b>

                                <br/>

                            	<table width="180">

                                    <tr>

                                        <td width="20%" style="background-color:#4DFF00" align="center"><input type="radio" name="risk" value="1" <?php if($row3['risk']==1 || $row3['risk']==0) echo 'checked="checked"';?>/> </td>

                                        <td width="20%" style="background-color:#CCFF00" align="center"><input type="radio" name="risk" value="2" <?php if($row3['risk']==2) echo 'checked="checked"';?>/></td>

                                        <td width="20%" style="background-color:#FFFF00" align="center"><input type="radio" name="risk" value="3" <?php if($row3['risk']==3) echo 'checked="checked"';?>/></td>

                                        <td width="20%" style="background-color:#FFB300" align="center"><input type="radio" name="risk" value="4" <?php if($row3['risk']==4) echo 'checked="checked"';?>/></td>

                                        <td width="20%" style="background-color:#FF3300" align="center"><input type="radio" name="risk" value="5" <?php if($row3['risk']==5) echo 'checked="checked"';?>/></td>

                                    </tr>

                                    <tr>

                                        <td width="20%" align="center">1</td>

                                        <td width="20%" align="center">2</td>

                                        <td width="20%" align="center">3</td>

                                        <td width="20%" align="center">4</td>

                                        <td width="20%" align="center">5</td>

                                    </tr>

                                </table>

                            	<br/>

                                <b>Total Accident</b>

                                <table width="100%">

                                	<tr>

                                    	<td width="25%">

                                        	Not At Fault:

                                        </td>

                                        <td width="75%">

                                        	<?php

												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license' AND status='Not At Fault'";

												$rs9=mysql_query($sql9);

												$row9=mysql_fetch_array($rs9);

												echo $row9['c'];

											?>

                                        </td>

                                    </tr>

                                    <tr>

                                    	<td width="25%">

                                        	Pending:

                                        </td>

                                        <td width="75%">

                                        	<?php

												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license' AND (status='Pending' OR status='')";

												$rs9=mysql_query($sql9);

												$row9=mysql_fetch_array($rs9);

												echo $row9['c'];

											?>

                                        </td>

                                    </tr>

                                     <tr>

                                    	<td width="25%">

                                        	At Fault:

                                        </td>

                                        <td width="75%">

                                        	<?php

												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license' AND (status='At Fault' OR status='Shared Liability')";

												$rs9=mysql_query($sql9);

												$row9=mysql_fetch_array($rs9);

												$atf = 0+$row9['c'];



												$sql9 = "SELECT * FROM drivers_license WHERE id='$license'";

												$rs9=mysql_query($sql9);

												$row9=mysql_fetch_array($rs9);

												$pers = $row9['persoonsNo'];

												if(trim($pers) !== ''){

													$sql9 = "SELECT * FROM drivers_license WHERE id!='$license' AND persoonsNo='$pers'";

													$rs9=mysql_query($sql9);

													while($row9 = mysql_fetch_array($rs9)){

														$tl = $row9['id'];

														if(trim($tl) !== ''){

															$sql8="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='".$tl."' AND (status='At Fault' OR status='Shared Liability')";

															$rs8=mysql_query($sql8);

															$row8=mysql_fetch_array($rs8);

															$atf = $atf + $row8['c'];

														}

													}

												}

												echo $atf;

											?>

                                           </td>

                                    </tr>

                                    <tr><td colspan="2">&nbsp;</td></tr>

                                    <tr>

                                    	<td>License No.:</td>

                                   		<td><?php echo $license;?></td>

                                    </tr>

                                    <tr>

                                    	<td>Persoons No.:</td>

                                   		<td><?php echo $pers;?></td>

                                    </tr>

                                </table>

                            </td>

                   		</tr>

                 	</table>

               	</tr>

                <?php

				 if($dr_found2==1){

				?>

                <tr><td colspan="2">&nbsp;</td></tr>

                <tr>

                	<td width="10">&nbsp;</td>

                	<td class="top-child_h" style="color:#148540;"><h4>Drivers License Owner</h4></td>

                </tr>

                <tr>

                	<td width="10">&nbsp;</td>

                	<td class="bottom-child">

                     <table width="100%">

                    	<tr>

                        	<td width="50"><a target="_blank" href="download.php?file=<?php echo $row6['loc'];?>"><img width="300" src="download.php?file=<?php echo $row6['loc'];?>" /></a><br/><span style="color:#C60; font-weight:bold">License Expiration Date: <span style="font-size:18px"><?php echo $row6['expireDate'];?></span></span>

                      	</td>

                        <td width="50%" valign="top">

                            	<b>Risk Factor</b>

                                <br/>

                            	<table width="180">

                                    <tr>

                                        <td width="20%" style="background-color:#4DFF00" align="center"><input type="radio" name="risk2" value="1" <?php if($row6['risk']==1 || $row6['risk']==0) echo 'checked="checked"';?>/> </td>

                                        <td width="20%" style="background-color:#CCFF00" align="center"><input type="radio" name="risk2" value="2" <?php if($row6['risk']==2) echo 'checked="checked"';?>/></td>

                                        <td width="20%" style="background-color:#FFFF00" align="center"><input type="radio" name="risk2" value="3" <?php if($row6['risk']==3) echo 'checked="checked"';?>/></td>

                                        <td width="20%" style="background-color:#FFB300" align="center"><input type="radio" name="risk2" value="4" <?php if($row6['risk']==4) echo 'checked="checked"';?>/></td>

                                        <td width="20%" style="background-color:#FF3300" align="center"><input type="radio" name="risk2" value="5" <?php if($row6['risk']==5) echo 'checked="checked"';?>/></td>

                                    </tr>

                                    <tr>

                                        <td width="20%" align="center">1</td>

                                        <td width="20%" align="center">2</td>

                                        <td width="20%" align="center">3</td>

                                        <td width="20%" align="center">4</td>

                                        <td width="20%" align="center">5</td>

                                    </tr>

                                </table>

                        		<br/>

                                <b>Total Accident</b>

                                <table width="100%">

                                	<tr>

                                    	<td width="25%">

                                        	Not At Fault:

                                        </td>

                                        <td width="75%">

                                        	<?php

												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license2' AND status='Not At Fault'";

												$rs9=mysql_query($sql9);

												$row9=mysql_fetch_array($rs9);

												echo $row9['c'];

											?>

                                        </td>

                                    </tr>

                                    <tr>

                                    	<td width="25%">

                                        	Pending:

                                        </td>

                                        <td width="75%">

                                        	<?php

												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license2' AND (status='Pending' OR status='')";

												$rs9=mysql_query($sql9);

												$row9=mysql_fetch_array($rs9);

												echo $row9['c'];

											?>

                                        </td>

                                    </tr>

                                     <tr>

                                    	<td width="25%">

                                        	At Fault:

                                        </td>

                                        <td width="75%">

                                        	<?php

												$sql9="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='$license2' AND (status='At Fault' OR status='Shared Liability')";

												$rs9=mysql_query($sql9);

												$row9=mysql_fetch_array($rs9);

												$atf = 0+$row9['c'];



												$sql9 = "SELECT * FROM drivers_license WHERE id='$license2'";

												$rs9=mysql_query($sql9);

												$row9=mysql_fetch_array($rs9);

												$pers = $row9['persoonsNo'];

												if(trim($pers) !== ''){

													$sql9 = "SELECT * FROM drivers_license WHERE id!='$license2' AND persoonsNo='$pers'";

													$rs9=mysql_query($sql9);

													while($row9 = mysql_fetch_array($rs9)){

														$tl = $row9['id'];

														if(trim($tl) !== ''){

															$sql8="SELECT count(sc_id) as c FROM service_req_extra WHERE `dr_license`='".$tl."' AND (status='At Fault' OR status='Shared Liability')";

															$rs8=mysql_query($sql8);

															$row8=mysql_fetch_array($rs8);

															$atf = $atf + $row8['c'];

														}

													}

												}

												echo $atf;

											?>

                                        </td>

                                    </tr>

                                     <tr><td colspan="2">&nbsp;</td></tr>

                                    <tr>

                                    	<td>License No.:</td>

                                   		<td><?php echo $license2;?></td>

                                    </tr>

                                    <tr>

                                    	<td>Persoons No.:</td>

                                   		<td><?php echo $pers;?></td>

                                    </tr>

                                </table>

                        </td>

                  		</tr>

                  	</table>

                  	</td>

               	</tr>

                <?php

				}

				?>

            	</table>

          	</td>

            <?php

				}

			?>

   	</tr>

       <?php

	   		$sql = "SELECT * FROM vehicle_com WHERE license='$lic'";

			$rs = mysql_query($sql);

			if(mysql_num_rows($rs)!=0){

			while($row=mysql_fetch_array($rs)){

		?>

        	<tr>

            	<td class="middle-child" colspan="2"><span style="color:red;font-weight:bold"><?php echo $row['comment'];?>

					<?php if(checkAdmin()){ ?></span>

					<a style="color:#DCB272" href="delete_veh_comment.php?id=<?php echo $row['id']?>&lic=<?php echo $lic?>">Delete</a>

                    </span>

					<a style="color:#DCB272" href="new_com.php?lic=<?php echo $lic;?>&id=<?php echo $row['id']?>&lic=<?php echo $lic?>">Edit</a>

                  	<?php } ?>

              	</td>

                <td colspan="3">&nbsp;</td>

          	</tr>

        <?php

				}

			}

	   ?>

        <?php

			if(strcmp($_REQUEST['submit'],'Submit')==0 || $_REQUEST['lic']){

				$lic = $_REQUEST['lic'];

				//Have to converty Date_Effictive to date to sort otherwise you cannot sort

				//$sql = "SELECT * FROM vehicles_2 WHERE LicPlateNo = '$lic' ORDER BY FIELD(VehStatus,'A','L','C'), STR_TO_DATE( `Date_Renewal` , '%m/%d/%Y' ) DESC, PolicyNo DESC";

				//$rs = mysql_query($sql);

				$tp=0;

				if(trim($lic)!==''){

				$sql = "SELECT * FROM VW_VEHICLE WHERE (LicPlateNo = '$lic' or LicPlateNo = '$licc') ".$extra." ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

				}

				else if(trim($name)!==''){

					$sql = "SELECT * FROM VW_VEHICLE WHERE Full_Name LIKE '%$name%' ".$extra." ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

				}

				else if(trim($_POST['license_no']!=='')){

					$sql = "SELECT * FROM VW_VEHICLE WHERE Driver1_License= '".$_POST['license_no']."' ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

				}

				$rs = mssql_query($sql);

				$rstemp=0;

				$in=1;

				if(mssql_num_rows($rs)==0){$in=0;}

				//if($in==0){

				/*if(0){

					//Connect to agent calc

					$host="129.121.4.207"; // Host name

					$db_username="nagicoab_reader"; // Mysql username

					$db_password="cEyVOG8q9KRwYOoV"; // Mysql password

					$db_name="nagicoab_agentcalc";

					$tc=1;

					$db2 = mysql_connect($host, $db_username, $db_password, true)or ($tc = 0);

					if($tc){

						mysql_select_db($db_name, $db2)or die("unable to access database1");

						$sql = "SELECT * FROM covernote WHERE registration_mark='$lic' AND void=0 AND approve >= 0";

						$rstemp = mysql_query($sql);

						$tp = 0;

						$cn=1;

					}

					else{

						echo 'Could not connect to covernote system';

					}

				}*/

				if($in==0 && mysql_num_rows($rstemp)==0){

					//$host="192.168.5.24"; // Host name

					//$db_username="web"; // Mysql username

					//$db_password="O&8Bd0&iq;A-"; // Mysql password

					//$db_name=DB_NAME; // Database name

					//$db1 = mysql_connect("$host", "$db_username", "$db_password")or die("cannot connect");

					$sql = "SELECT * FROM non_client_extra WHERE id = '$lic'";

					$rstemp = mysql_query($sql);

					$tp=1;

					$cn=0;

				}



				$found = 1;

				if($in==0 && ($rstemp==0 || mysql_num_rows($rstemp)==0)){



					echo ' <tr>

        	<td class="bottom-child" colspan="2">Not Found</td>

			<td colspan="3">&nbsp;</td>

        </tr>';

					$found = 0;

				}

				else{

					if($in==1){

					if(trim($lic)!==''){

						$sql = "SELECT * FROM VW_VEHICLE WHERE (LicPlateNo = '$lic' or LicPlateNo = '$licc') ".$extra." ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

					}

				else if(trim($name)!==''){

						$sql = "SELECT * FROM VW_VEHICLE WHERE Full_Name LIKE '%$name%' ".$extra." ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";



					}

				else if(trim($_POST['license_no']!=='')){

					$sql = "SELECT * FROM VW_VEHICLE WHERE Driver1_License= '".$_POST['license_no']."' ORDER BY

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Effective DESC, PolicyNo DESC";

				}



						$rs = mssql_query($sql);

						$row = mssql_fetch_array($rs);

					}

					else{

						$row = mysql_fetch_array($rstemp);

					}



					$pol = $row['PolicyNo'];



		?>

        <form name="new_com" action="new_com.php?lic=<?php echo $lic;?>" method="post">

       <tr><td class="middle-child" colspan="2"><input type="submit" name="submit" value="New Comment"></td><td colspan="3">&nbsp;</td></tr>

       </form>

       <tr><td class="middle-child" colspan="2">&nbsp;</td><td colspan="3">&nbsp;</td></tr>

        <tr>

        	<?php

				$sql2 = "SELECT * FROM `blacklist` WHERE `a_number`='$lic'";

				$rs2 = mysql_query($sql2);

			?>

        	<td class="middle-child" colspan="2">Found

            	<?php

					if(mysql_num_rows($rs2) > 0){

						$row2 = mysql_fetch_array($rs2);

						echo '<span style="color:red;font-weight:bold"> BLACKLISTED reason: '.$row2['comment'].'</span>';

					}

				?>

                <?php

					echo $e;

				?>

            </td>

           	<td colspan="3">&nbsp;</td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Status:</td>

            <td class="middle-right-child" width="<?php echo $col2?>" <?php

			if(strcmp($row['Status'],'A')==0){

				echo 'style="color:#0C0;font-weight:bold;font-size:18px"' ;

			}

			else if(strcmp($row['Status'],'C')==0){

				echo 'style="color:#F00;font-weight:bold;font-size:18px"' ;

			}

			else{

				echo 'style="color:#F90;font-weight:bold;font-size:18px"' ;

			}

			?> ><?php if($tp){echo 'None Client';} else if($cn){echo 'Covernote '.$row['covernoteno'];}else {$lic=$row['LicPlateNo']; echo $row['VehStatus']." (".$lic.")"; }?></td>

        </tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Name:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php

			if($cn){ echo $row['name'];}

			else{	echo $row['Full_Name'].$row['fname'].' '.$row['lname']; }?>



            </td>

        </tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Drivers License:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php

			if($cn){ echo $row['driversLicense'];}

			else if($tp){echo $row['licenseNo'];}

			else {echo $license;}



			?></td>

        </tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Phone:</td>

            <?php

							$phone = '';

							if(strcmp(trim($row['WorkPhone']),'')!=0){

								$phone = $row['WorkPhone'];

							}

							if(strcmp(trim($row['HomePhone']),'')!=0){

								if(strcmp($phone,'')==0){

									$phone = $row['HomePhone'];

								}

								else{

									$phone = $phone.' / '.$row['HomePhone'];

								}

							}

							if(strcmp(trim($row['MobilePhone']),'')!=0){

								if(strcmp($phone,'')==0){

									$phone = $row['MobilePhone'];

								}

								else{

									$phone = $phone.' / '.$row['MobilePhone'];

								}

							}

							if(strcmp(trim($row['phone']),'')!=0){

								if(strcmp($phone,'')==0){

									$phone = $row['phone'];

								}

								else{

									$phone = $phone.' / '.$row['phone'];

								}

							}

							if(strcmp(trim($row['mobile']),'')!=0){

								if(strcmp($phone,'')==0){

									$phone = $row['mobile'];

								}

								else{

									$phone = $phone.' / '.$row['mobile'];

								}

							}

			?>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php echo $phone;?></td>

        </tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Address:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php echo $row['Address1'].$row['address'];?></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Make, Model and Year:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php

			if($cn){echo $row['make'].' '.$row['type'].' '.$row['year']; $tyear = $row['year'];}

			else if($tp){echo $row['make'].' '.$row['model'].' '.$row['year'];}

			else {echo $row['Make'].' '.$row['Model'].' '.$row['YearMake']; $tyear = $row['YearMake'];}

			?></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Policy Number<?php if($tp) {echo ' / Company';}?>:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php echo $row['PolicyNo']; $temp_pol = $row['PolicyNo'];?>&nbsp;&nbsp;<span style="color:blue"><?php

            	echo getPolicyMadeBy($row['PolicyNo']);

			?></span><?php if($tp){

				$inat = $row['insured_at'];

				$sql12 = "SELECT * FROM `insurance_company` WHERE `id`='$inat'";

				$rs12 = mysql_query($sql12);

				$row12 = mysql_fetch_array($rs12);

				echo $row12['name'];

			}?></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Premium / Balance / Deductible:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php

				if($_SESSION['user_level'] >= POWER_LEVEL){

					echo number_format((float)$row['Premium']+(float)$row['PolicyFee'],2);

					echo ' / <a style="color:red">'.number_format($row['AmountDeb'],2).'</a> / '.$row['Deduct'];};



			?></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Car Insured Since:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php

			if($cn){

				$tdate = new datetime (substr($row['from'],0,10));

				echo date_format($tdate,"d F, Y");

           	}

			else if($tp){

				$tdate = new datetime (substr($row['insured_from'],0,10));

				if(trim($row['insured_from'])!==''){

					echo date_format($tdate,"d F, Y");

				}

			}

			else{

				$tdate = new datetime (substr(date("d-M-y", strtotime($row['Date_Application'])),0,10));

				echo date_format($tdate,"d F, Y");

			}

			?>

            </td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Insurance Period:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php

			if($cn) {echo date_format($tdate,"d F, Y").' ('.$row['period'].')';}

			else if($tp){

				$fdate = new datetime(substr($row['insured_from'],0,10));

				$tdate = new datetime(substr($row['insured_to'],0,10));

				echo date_format($fdate,"d M, Y").' - '.date_format($tdate,"d M, Y");

			}

			else {
			$fdate = new datetime(substr(date("d-M-y", strtotime($row['VehDate_Effective'])),0,10));

			$tdate = new datetime(substr(date("d-M-y", strtotime($row['VehDate_Renewal'])),0,10));

			echo date_format($fdate,"d M, Y").' - '.date_format($tdate,"d M, Y");}

			?></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Body Type / Doors:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php  echo $row['BodyType'].' / '.$row['Seats'];?></td>

        </tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Color / Year:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php echo $row['YearMake'].' / '.$row['Color'].$row['color'];?></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">New Value / Day Value:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php $value=$row['value'];

			if($cn) {echo number_format($row['value'],2);}

			else {$value=$row['VehicleValue']; echo number_format($row['VehicleValue'],2);}

			?> / <span style="color:#03C"><?php echo number_format(dayValue($tyear,$value,$row['VehUse']),2)?></span></td>

        </tr>

        <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Coverage and Use:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php

			if($cn){

				$sql2 = "SELECT * FROM coverage WHERE id = '$row[coverage]'";

				$rs2 = mysql_query($sql2);

				$row2 = mysql_fetch_array($rs2);

				$t1 = $row2['short'];

				$sql2 = "SELECT * FROM vehicleuse WHERE id = '$row[vuse]'";

				$rs2 = mysql_query($sql2);

				$row2 = mysql_fetch_array($rs2);

				$t2 = $row2['code'];

				echo $t1.'/'.$t2;



			}

			else if($tp){ echo $row['vehicle_coverage'].' /'.$row['vehicle_use'];}

			else{ echo $row['VehCoverage'].'/'.$row['VehUse'];}

			?></td>

        </tr>

         <tr>

        	<td class="middle-left-child" width="<?php echo $col1?>">Agent:</td>

            <td class="middle-right-child" width="<?php echo $col2?>"><?php

			echo $row['AgentName']?>

            </td>

        </tr>

		<tr>



        	<td class="middle-child" colspan="2">

            	<form name="new_rec" action="new_sc.php" method="post">

            	<input type="hidden" name="lic" value="<?php echo $lic;?>">

                <input type="hidden" name="pn" value="<?php echo $policy;?>">

                <input type="submit" name="submit" value="New Service Request">

                </form>

            </td>

            <td colspan="3">&nbsp;</td>

        </tr>

        <tr>

        	<td class="bottom-child" colspan="2">

            	<div class="buttonwrapper">

					<a class="squarebutton" href="javascript:popacc('ins_drivers_license.php?license=<?php if($row['LicenseNo']!='') {echo $row['LicenseNo'];} else {echo $lic;}?>');"><span>Insert/Update Drivers License</span></a>

				</div>

            </td>

            <td colspan="3">&nbsp;</td>

        </tr>

        <?php



				}

			}

		?>



        <?php if (isset($_REQUEST['lic'])){



			if($dr_found && !$found){

				echo '<tr><td colspan="5">&nbsp;</td></tr>';

				echo '<tr><td colspan="5">&nbsp;</td></tr>';

				echo '<tr><td colspan="5">&nbsp;</td></tr>';

				echo '<tr><td colspan="5">&nbsp;</td></tr>';

				echo '<tr><td colspan="5">&nbsp;</td></tr>';

				echo '<tr><td colspan="5">&nbsp;</td></tr>';

			}

		?>



        <tr><td colspan="5">&nbsp;</td></tr>

        <tr><td class="top-child_h" colspan="5" style="color:#148540"><h4>History</h4></td></tr>

        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>

        <tr><td colspan="5">

        	<table width="100%" cellspacing="0">



        	 <?php

			 		//$host="192.168.5.24"; // Host name

					//$db_username="web"; // Mysql username

					//$db_password="O&8Bd0&iq;A-"; // Mysql password

					//$db_name=DB_NAME; // Database name

					//$db1 = mysql_connect("$host", "$db_username", "$db_password")or die("cannot connect");

					$sql8 = "SELECT * FROM `roadservice`.`service_req` WHERE `a_number`='$lic' AND `delete` = 0 order by STR_TO_DATE( `opendt` , '%m-%d-%Y' ) DESC, id DESC";

					$rs8 = mysql_query($sql8);

					if(mysql_num_rows($rs8)!=0 && trim($lic)!==''){

						$col1 = 45;

						$col2 = 132;

						$col3 = 75;

						$col4 = 50;

						?>

                        	<tr class="thead">

                                <td class="middle-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>

                                <td width="<?php echo $col2+15;?>">Date</td>

                                <td width="<?php echo $col2;?>">Car</td>

                                <td width="<?php echo $col3;?>">A Number</td>

                                <td width="<?php echo $col2;?>">Location</td>

                                <td width="<?php echo $col2-15;?>">Job</td>

                                <td width="<?php echo $col4;?>">Attendee</td>

                                <td width="<?php echo $col4;?>">Charged</td>

                                <td width="<?php echo $col4;?>">Insured</td>

                                <td width="<?php echo $col4;?>">Image</td>

                                <td class="middle-right-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Status</td>

                            </tr>

                        <?php while($row = mysql_fetch_array($rs8)){

				?>

                			<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>

                                <td class="middle-left-child" style="background-color:#E1DDDC" <?php

                                if($row['master_sc']!=0){

                                    echo 'style="background-color:#6F3"';

                                }

                                else if($row['status']==4){

                                    echo 'style="background-color:#EB6C2C"';

                                }

                                else if($row['present'] == 0 && $row['job']==7){

                                    echo 'style="background-color:#FF0000"';

                                }

                                else if($row['charged']>0 && $row['money_delivered'] == 0 && $row['status'] != 3){

                                    echo 'style="background-color:#800080"';

                                }



                                ?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>

                                <td width="<?php echo $col2+10;?>"><?php echo substr($row['opendt'],0,16);?></td>

                                 <td width="<?php echo $col2;?>"><?php echo $row['car'];?></td>

                              <td width="<?php echo $col3;?>"><?php echo $row['a_number'];?></td>

                                <td width="<?php echo $col2;?>"><?php echo $row['location'];?></td>

                                <td width="<?php echo $col2-15;?>"><?php

                                    $jid = $row['job'];

                                    $sql2 = "SELECT * FROM jobs where id = '$jid'";

                                    $rs2 = mysql_query($sql2);

                                    $row2 = mysql_fetch_array($rs2);

                                    echo $row2['description'];

                                ?></td>

                                <td width="<?php echo $col3;?>"><?php

                                    $aid = $row['attendee_id'];

                                    $sql2 = "SELECT * FROM attendee where id = '$aid'";

                                    $rs2 = mysql_query($sql2);

                                    $row2 = mysql_fetch_array($rs2);

                                    echo $row2['s_name'];

                                ?></td>

                                <td width="<?php echo $col3;?>">

                                    <?php echo number_format($row['charged'],2);?>

                                </td>

                                <td width="<?php echo $col3;?>"><?php

                                    if($row['insured']){

                                        echo 'Yes';

                                    }

                                    else{

                                        echo 'No';

                                    }

                                ?></td>

                                <td width="<?php echo $col3;?>">

                                    <?php

                                        if (is_dir('rrimage/'.$row['id'])){

                                            echo 'Yes';

                                        }

                                        else{

                                            echo 'No';

                                        }

                                    ?>

                                </td>

                                <td class="middle-right-child" style="background-color:#E1DDDC" width="<?php echo $col4;?>"><?php

                                    $sid = $row['status'];

                                    $sql2 = "SELECT * FROM status WHERE id = '$sid'";

                                    $rs2 = mysql_query($sql2);

                                    $row2 = mysql_fetch_array($rs2);

                                    echo $row2['status'];

                                ?>

                                </td>

                            </tr>

                <?php	} //End While Loop

					} //End if Loop

				?>

                <tr><td class="bottom-child" colspan="11">&nbsp;</td></tr>

        	</table>

        </td></tr>



        <tr><td colspan="5">&nbsp;</td></tr>

        <tr><td class="top-child_h" colspan="5" style="color:#148540"><h4>Rental</h4></td></tr>

        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>

        <tr><td colspan="5">

        	<table width="100%" cellspacing="0">



        	 <?php



					$sql8 = "SELECT * FROM `rental` WHERE `policy_no`='".$temp_pol."' order by STR_TO_DATE( `time_out` , '%m-%d-%Y %h:%m' ) DESC";

					$rs8 = mysql_query($sql8);

					if(mysql_num_rows($rs8)!=0 && trim($temp_pol) !== ''){

						$col1 = 45;

						$col2 = 132;

						$col3 = 75;

						$col4 = 50;

						?>

                        	<tr class="thead">

                                <td class="middle-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>

                                <td width="<?php echo $col2+15;?>">Request By</td>

                                <td width="<?php echo $col2;?>">Car</td>

                                <td width="<?php echo $col3;?>">A Number</td>

                                <td width="<?php echo $col2;?>">Time Out</td>

                                <td width="<?php echo $col2-15;?>">Time In</td>

                                <td width="<?php echo $col4;?>">ClaimsNo</td>

                                <td width="<?php echo $col4;?>">&nbsp;</td>

                                <td width="<?php echo $col4;?>">Total Days</td>

                                <td width="<?php echo $col4;?>">Total Charged</td>

                                <td class="middle-right-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Status</td>

                            </tr>

                        <?php while($row = mysql_fetch_array($rs8)){

				?>

                			<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>

                                <td class="middle-left-child" style="background-color:#E1DDDC" width="<?php echo $col1;?>"><a style="color:#DCB272" href="rental_detail.php?id=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>

                                <td width="<?php echo $col2+10;?>"><?php

                               		$sql10 = "SELECT * FROM rental_request WHERE id='".$row['requested_by']."'";

									$rs10 = mysql_query($sql10);

									$row10 = mysql_fetch_array($rs10);

									echo $row10['name'];

								?></td>

                                 <td width="<?php echo $col2;?>"><?php

                                 	$sql10 = "SELECT * FROM rental_vehicle WHERE id='".$row['rental_vehicle_id']."'";

									$rs10 = mysql_query($sql10);

									$row10 = mysql_fetch_array($rs10);

									echo $row10['make'].' '.$row10['model'];

								 	$rate = $row10['rental'];

								 ?></td>

                              <td width="<?php echo $col3;?>"><?php echo $row10['licenseplate'];?></td>

                                <td width="<?php echo $col2;?>"><?php echo $row['time_out'];?></td>

                                <td width="<?php echo $col2-15;?>"><?php echo $row['time_in'];?></td>

                                <td width="<?php echo $col3;?>"><?php echo $row['claimNo'];?></td>

                                <td width="<?php echo $col3;?>">&nbsp;</td>

                                <td width="<?php echo $col3;?>"><?php

									list($month,$day,$year) = explode('-',substr($row['time_out'],0,10));

									list($date,$time) = explode(' ',$row['time_out']);

									$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

									$date2 = new DateTime(date("Y-m-d H:i"));

									if(strlen(trim($row['time_in']))!=0){

										list($month,$day,$year) = explode('-',substr($row['time_in'],0,10));

										list($date,$time) = explode(' ',$row['time_in']);

										$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

									}

									$interval = $date1->diff($date2);

									if($interval->h==0){

										echo $interval->days;

									}

									else{

										echo ($interval->days+1);

									}



								?></td>

                                <td width="<?php echo $col3;?>"><?php

									list($month,$day,$year) = explode('-',substr($row['time_out'],0,10));

									list($date,$time) = explode(' ',$row['time_out']);

									$date1 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

									$date2 = new DateTime(date("Y-m-d H:i"));

									if(strlen(trim($row['time_in']))!=0){

										list($month,$day,$year) = explode('-',substr($row['time_in'],0,10));

										list($date,$time) = explode(' ',$row['time_in']);

										$date2 = new DateTime($year.'-'.$month.'-'.$day.' '.$time);

									}

									$interval = $date1->diff($date2);

									if($interval->h==0){

										echo number_format($rate*$interval->days,2);

									}

									else{

										echo number_format($rate*($interval->days+1),2);

									}



								?></td>

                                <td class="middle-right-child" style="background-color:#E1DDDC" width="<?php echo $col4;?>"><?php echo $row['status']; ?></td>

                            </tr>

                <?php	} //End While Loop

					} //End if Loop

				?>

                <tr><td class="bottom-child" colspan="11">&nbsp;</td></tr>

        	</table>

        </td></tr>





         <?php if($found && strcmp(trim($pol),'')!=0){ ?>

        <tr><td class="top-child_h" colspan="5" style="color:#148540"><h4>History Other</h4></td></tr>

        <tr><td class="middle-child" colspan="5">&nbsp;</td></tr>



        <tr><td colspan="5">

        	<table width="100%" cellspacing="0">

        	 <?php

					$sql2 = "SELECT * FROM service_req WHERE `pol`='$pol' AND `delete` = 0  AND `a_number`!='$lic' order by STR_TO_DATE( `opendt` , '%m-%d-%Y' ) DESC, id DESC";

					$rs8 = mysql_query($sql2);

					if(mysql_num_rows($rs8)!=0){

						$col1 = 45;

						$col2 = 132;

						$col3 = 75;

						$col4 = 50;

						?>

                        	<tr class="thead">

                                <td class="middle-left-child" style="background-color:#ECF65C;" width="<?php echo $col1;?>">ID</td>

                                <td width="<?php echo $col2+15;?>">Date</td>

                                <td width="<?php echo $col2;?>">Car</td>

                                <td width="<?php echo $col3;?>">A Number</td>

                                <td width="<?php echo $col2;?>">Location</td>

                                <td width="<?php echo $col2-15;?>">Job</td>

                                <td width="<?php echo $col4;?>">Attendee</td>

                                <td width="<?php echo $col4;?>">Charged</td>

                                <td width="<?php echo $col4;?>">Insured</td>

                                <td width="<?php echo $col4;?>">Image</td>

                                <td class="middle-right-child" style="background-color:#ECF65C;" width="<?php echo $col4;?>">Status</td>

                            </tr>

                        <?php while($row = mysql_fetch_array($rs8)){

				?>

                			<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>

                                <td class="middle-left-child" style="background-color:#E1DDDC" <?php

                                if($row['master_sc']!=0){

                                    echo 'style="background-color:#6F3"';

                                }

                                else if($row['status']==4){

                                    echo 'style="background-color:#EB6C2C"';

                                }

                                else if($row['present'] == 0 && $row['job']==7){

                                    echo 'style="background-color:#FF0000"';

                                }

                                else if($row['charged']>0 && $row['money_delivered'] == 0 && $row['status'] != 3){

                                    echo 'style="background-color:#800080"';

                                }



                                ?> width="<?php echo $col1;?>"><a style="color:#DCB272" href="edit_sc.php?sc=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>

                                <td width="<?php echo $col2+10;?>"><?php echo substr($row['opendt'],0,16);?></td>

                                 <td width="<?php echo $col2;?>"><?php echo $row['car'];?></td>

                              <td width="<?php echo $col3;?>"><?php echo $row['a_number'];?></td>

                                <td width="<?php echo $col2;?>"><?php echo $row['location'];?></td>

                                <td width="<?php echo $col2-15;?>"><?php

                                    $jid = $row['job'];

                                    $sql2 = "SELECT * FROM jobs where id = '$jid'";

                                    $rs2 = mysql_query($sql2);

                                    $row2 = mysql_fetch_array($rs2);

                                    echo $row2['description'];

                                ?></td>

                                <td width="<?php echo $col3;?>"><?php

                                    $aid = $row['attendee_id'];

                                    $sql2 = "SELECT * FROM attendee where id = '$aid'";

                                    $rs2 = mysql_query($sql2);

                                    $row2 = mysql_fetch_array($rs2);

                                    echo $row2['s_name'];

                                ?></td>

                                <td width="<?php echo $col3;?>">

                                    <?php echo number_format($row['charged'],2);?>

                                </td>

                                <td width="<?php echo $col3;?>"><?php

                                    if($row['insured']){

                                        echo 'Yes';

                                    }

                                    else{

                                        echo 'No';

                                    }

                                ?></td>

                                <td width="<?php echo $col3;?>">

                                    <?php

                                        if (is_dir('rrimage/'.$row['id'])){

                                            echo 'Yes';

                                        }

                                        else{

                                            echo 'No';

                                        }

                                    ?>

                                </td>

                                <td class="middle-right-child" style="background-color:#E1DDDC" width="<?php echo $col4;?>"><?php

                                    $sid = $row['status'];

                                    $sql2 = "SELECT * FROM status WHERE id = '$sid'";

                                    $rs2 = mysql_query($sql2);

                                    $row2 = mysql_fetch_array($rs2);

                                    echo $row2['status'];

                                ?>

                                </td>

                            </tr>

                <?php	} //End While Loop

					} //End if Loop

				?>

                <tr><td class="bottom-child" colspan="11">&nbsp;</td></tr>

           	<?php } //end if found ?>

        	</table>

        </td></tr>



        <?php } // end if (isset($_REQUEST['lic']))?>







	</table>

<script type="text/javascript" src="js/functions.js">

</script>

</body>

</html>
