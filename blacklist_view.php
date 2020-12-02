<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";

if($_SESSION['user_level'] < RR_LEVEL){
	header("Location: index.php");
	exit();
}

$col1 = 5;
$col2 = 7;
$col3 = 22;
$col4 = 10;
$col5 = 15;
$col6 = 35;
$col7 = 6;

echo menu();

?>

	<table width="900">
    	<tr>
        	<td colspan="7" align="center" style="border:0;color:#148540"><h3>Blacklist</h3></td>
        </tr>
        <tr><td colspan="7">&nbsp;</td></tr>
        <tr class="thead">
        	<td width="<?php echo $col1;?>%">id</td>
            <td width="<?php echo $col2;?>%">Client No.</td>
            <td width="<?php echo $col3;?>%">Name</td>
            <td width="<?php echo $col4;?>%">License Plate</td>
            <td width="<?php echo $col5;?>%">Timestamp</td>
        	<td width="<?php echo $col6;?>%">Comment</td>
            <td width="<?php echo $col7;?>%">Image</td>
        </tr>
        <?php
			$sql = "SELECT * FROM `blacklist`";
			$rs = mysql_query($sql);
			$bg = 0;
			while($row = mysql_fetch_array($rs)){
		?>
        <tr <?php if($bg==0){ echo 'style="background-color:#E1DDDC"';}?>>
        	<td width="<?php echo $col1;?>%"><a style="color:#DCB272" href="blacklist_detail.php?id=<?php echo $row['id'];?>"><?php echo $row['id'];?></a></td>
            <td width="<?php echo $col2;?>%"><?php echo $row['ClientNo'];?></td>
            <td width="<?php echo $col3;?>%"><?php echo 'name';?></td>
            <td width="<?php echo $col4;?>%"><?php echo $row['a_number'];?></td>
            <td width="<?php echo $col5;?>%"><?php echo $row['timestamp'];?></td>
        	<td width="<?php echo $col6;?>%"><?php echo $row['comment'];?></td>
            <td width="<?php echo $col7;?>%"><?php
            	if(is_dir('blimage/'.$row['id'])){
					$dirname = "blimage/".$row['id'];
					$images = scandir($dirname);
					$ignore = Array(".", "..");
					$hasimage = 'No';
					foreach($images as $curimg){
						if(!in_array($curimg, $ignore)) {
							//echo '<a style="color:#DCB272" href="download.php?file='.$dirname.'/'.$curimg.'">Download Image</a>';	
							$hasimage = 'Yes';
						}
					}
					echo $hasimage;
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
 	</table>
</body>
</html>