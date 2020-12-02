<?php



include 'dbc.php';
require_once($_SERVER['DOCUMENT_ROOT']."/support/encryption_class.php");

//error_reporting(E_ALL);
//ini_set('display_errors', '1');


date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";





if(empty($_REQUEST['page'])){

	header("location:incoming.php?page=1");		

}

else{

	$page=$_REQUEST['page'];	
}

$rs2=$db2->query("SELECT * FROM settings");
$row2=$rs2->fetch_assoc();
$t=new cm_encryption();
$t->my_decrypt($row2['freepbx_password_enc']) ;


$link3 = mysql_connect($row2['freepbx_host'].':'.$row2['freepbx_port'], $row2['freepbx_username'], $t->dc_data) or die("Couldn't make connection.");
$db3 = mysql_select_db($row2['freepbx_database'], $link3) or die("Couldn't select database");



echo menu();





?>

<meta http-equiv="refresh" content="15" >

<table width="1200" cellspacing="0">

    	<tr>

        	<td colspan="11" align="center" style="border:0;color:#148540"><h8 style="color:#FFF">Incoming Calls 191</h8></td>

        </tr>

        

        <tr><td colspan="11">&nbsp;</td></tr>

        <tr>

        	<td class="top-left-child" style="background-color:#ECF65C;" width="20%">Date Time</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="12%">Disposition</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="10%">Number</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="10%">License Plate</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="15%">Car</td>

            <td class="top-center-child" style="background-color:#ECF65C;" width="8%">Coverage</td>

			<td colspan="7" class="top-right-child" style="background-color:#ECF65C;" width="35%">Full Name</td>



        </tr>  

        <?php		

			$lf=($page*25)-25;

			$lt=$page*25;		

			$sql2 = "SELECT * FROM asteriskcdrdb.cdr WHERE clid LIKE '%RoadService:%' and dst IN (191,602) ORDER BY calldate DESC LIMIT ".$lf.", 25";

			$rs2=mysql_query($sql2,$link3);

			while($row = mysql_fetch_array($rs2)){

				$filter='';

				

		?> 

        	<tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>

                <td class="middle-left-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> >

					<?php echo substr($row['calldate'],5,2).'-'.substr($row['calldate'],8,2).'-'.substr($row['calldate'],0,4).'&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:blue">'.substr($row['calldate'],11,5).'</span>'; ?>

               	</td>

                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> >

                	<span <?php if($row['disposition']==='NO ANSWER'){ echo 'style="color:red;"' ; } else { echo 'style="color:green;"' ;} ?>>

					<?php echo $row['disposition'];?>

                    </span>

                </td>

                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> >

					<?php echo  '<a href="index.php?phone='.$row['src'].'">'.$row['src'].'</a>';?>

                </td>

                 <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> >

					<?php

						$phone=$row['src'];

						$temp = substr($row['src'],0,3)."-".substr($row['src'],3);	

							//echo $temp;

						$filter = "(`AddPhone` = '$phone' OR `AddPhone`= '$temp')";

						$sql3="SELECT * FROM `service_req` WHERE ".$filter." ORDER BY id DESC";

						$rs3=mysql_query($sql3,$link);

						if(mysql_num_rows($rs3)>0){

							$row3=mysql_fetch_array($rs3);

							echo '<a href="check_ins.php?lic='.$row3['a_number'].'">'.$row3['a_number'].'</a>';

						}

					?>

                </td>

                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> >

					<?php 

						if(mysql_num_rows($rs3)>0){

							echo $row3['car'];

						}

					?>

                </td>

                <td class="middle-none-child"  <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?> >

					<?php 

						$lic=$row3['a_number'];

						$policy=$row3['pol'];

						

						$sql4 = "SELECT * FROM VW_VEHICLE WHERE LicPlateNo = '$lic' AND PolicyNo LIKE '$policy' ORDER BY 

				CASE	WHEN VehStatus='A' THEN 1

						WHEN VehStatus='L' THEN 2

						WHEN VehStatus='C' THEN 3

				End

				, Date_Renewal DESC, PolicyNo DESC";

					$params = array();

					$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

					$rs4 = mssql_query($sql4);

					

					if(mssql_num_rows($rs4)!=0 && mysql_num_rows($rs3)!=0 ){

						$row4 = mssql_fetch_array($rs4);

						echo $row4['VehCoverage'].'/'.$row4['VehUse'];

					}

					

					?>

                </td>

             	<td colspan="7" class="middle-right-child" <?php if($bg==0){ echo 'style="background-color:#E1DDDC;"';} ?>>	

                	<?php 

            		if(mssql_num_rows($rs4)!=0 && mysql_num_rows($rs3)!=0 ){

						echo $row4['Full_Name'];

					}

					?>

              	</td>

        	</tr> 

        <?php

			if($bg == 0){

				$bg = 1;

			}

			else{

				$bg = 0;	

			}

			}

		?>

        <?php

			$bpage=$page-5;

			$epage=$page+5;

			if($bpage<=0){

				//Check if begin page is < 0

				$bpage=1;	

			}

			$out='';

			if($bpage>1){

				// add less

				$out='<a href="incoming.php?page='.($bpage-1).'">Less</a>';	

			}

			while($bpage <= $epage){

				if($bpage==$page){

					$out=$out.' <a href="incoming.php?page='.($bpage).'"><'.$bpage.'></a>';

				}

				else{

					$out=$out.' <a href="incoming.php?page='.($bpage).'">'.$bpage.'</a>';

				}

				$bpage++;	

			}

			$out=$out.' <a href="incoming.php?page='.($epage+1).'">More</a>';	

		?>

        <tr><td class="middle-child" colspan="12" align="left"><?php echo $out;?></td></tr>

        

 </table>



</body>

</html>