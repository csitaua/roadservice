<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

echo menu();
$col1 = 100;
$col2 = 275;

?>

<form name="new_te" action="rec_te.php" method="post">
	<table width="900">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h3>New Travel Insurance Emergency</h3></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">Name:</td>
            <td colspan="3"><input type="text" style="background-color:#FAD090" name="name" size="30" /></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Address 1:</td>
            <td colspan="3"><input type="text" style="background-color:#FAD090" name="address1" size="50" /></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Address 2:</td>
            <td colspan="3"><input type="text" style="background-color:#FAD090" name="address2" size="50" /></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">City:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="city" size="20" /></td>
            <td width="<?php echo $col1;?>">State:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="state" size="20" /></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Country:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="country" size="20" /></td>
            <td width="<?php echo $col1;?>">Zip:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="zip" size="10" /></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Phone 1:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="phone1" size="20" /></td>
            <td width="<?php echo $col1;?>">Phone 2:</td>
            <td width="<?php echo $col2;?>"><input type="text" style="background-color:#FAD090" name="phone2" size="20" /></td>
        </tr>
        <tr>
        	<td width="<?php echo $col1;?>">Emergency Type:</td>
            <td colspan="3"><select name="em">
			<?php
            	$sql = "SELECT * FROM emergency ORDER BY description";
				$rs = mysql_query($sql);
				while($row = mysql_fetch_array($rs)){
			?>
            	<option value="<?php echo $row['id'];?>"><?php echo $row['description'];?></option>
            <?php } ?>
            </select></td>
        </tr>
         
        <tr>
        	<td width="<?php echo $col1;?>">Date Time:</td>
        	<td width="<?php echo $col2;?>">
            	<input type="text" id="datetime" name="datetime" readonly="readonly"/>
  <button id="opendtbutton">
    <img src="anytime/calendar.png" alt="[calendar icon]"/>
  </button>
  <script>
    $('#opendtbutton').click(
      function(e) {
        $('#datetime').AnyTime_noPicker().AnyTime_picker({format: "%m-%d-%Y %H:%i"}).focus();
        e.preventDefault();
      } );
  </script>
            </td>
            <td colspan="2" align="right"><input type="submit" name="submit" value="Submit"/></td>
            <td>&nbsp;</td>
        </tr>
    </table>
</form>
<script  type="text/javascript">
var frmvalidator = new Validator("new_te");
 frmvalidator.addValidation("name","req","Name is required");
 frmvalidator.addValidation("address1","req","Address1 is required");
 frmvalidator.addValidation("country","req","Country is required");
 frmvalidator.addValidation("phone1","req","Phone1 is required");
 frmvalidator.addValidation("datetime","req","Date and time is required");
</script>
</body>
</html>